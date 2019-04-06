<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blog_Settings' ) ) {

	/**
	 * Define Jet_Blog_Settings class
	 */
	class Jet_Blog_Settings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		public $key      = 'jet-blog-settings';
		public $builder  = null;
		public $settings = null;

		/**
		 * Init page
		 */
		public function init() {

			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_controls' ), 5 );

			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );
			add_action( 'init', array( $this, 'save' ), 40 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );

		}

		/**
		 * Initialize page builder module if required
		 *
		 * @return void
		 */
		public function init_builder() {

			if ( isset( $_REQUEST['page'] ) && $this->key === $_REQUEST['page'] ) {

				$builder_data = jet_blog()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

				$this->builder = new CX_Interface_Builder(
					array(
						'path' => $builder_data['path'],
						'url'  => $builder_data['url'],
					)
				);
			}

		}

		/**
		 * Show saved notice
		 *
		 * @return bool
		 */
		public function saved_notice() {

			if ( ! isset( $_GET['settings-saved'] ) ) {
				return false;
			}

			$message = esc_html__( 'Settings saved', 'jet-blog' );

			printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );

			return true;

		}

		/**
		 * Save settings
		 *
		 * @return void
		 */
		public function save() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			if ( ! isset( $_REQUEST['action'] ) || 'save-settings' !== $_REQUEST['action'] ) {
				return;
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$current = get_option( $this->key, array() );
			$data    = $_REQUEST;

			unset( $data['action'] );

			foreach ( $data as $key => $value ) {
				$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );
			}

			update_option( $this->key, $current );

			$redirect = add_query_arg(
				array( 'dialog-saved' => true ),
				$this->get_settings_page_link()
			);

			wp_redirect( $redirect );
			die();

		}

		/**
		 * Return settings page URL
		 *
		 * @return string
		 */
		public function get_settings_page_link() {

			return add_query_arg(
				array(
					'page' => $this->key,
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->key, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

		}

		/**
		 * Register add/edit page
		 *
		 * @return void
		 */
		public function register_page() {

			add_submenu_page(
				'elementor',
				esc_html__( 'JetBlog Settings', 'jet-blog' ),
				esc_html__( 'JetBlog Settings', 'jet-blog' ),
				'manage_options',
				$this->key,
				array( $this, 'render_page' )
			);

		}

		/**
		 * Register controls for settings page
		 *
		 * @return void
		 */
		public function register_controls() {

			if ( ! isset( $_REQUEST['page'] ) || $this->key !== $_REQUEST['page'] ) {
				return;
			}

			$this->builder->register_section(
				array(
					'jet_blog_settings' => array(
						'type'   => 'section',
						'scroll' => false,
						'title'  => esc_html__( 'JetBlog Settings', 'jet-blog' ),
					),
				)
			);

			$this->builder->register_form(
				array(
					'jet_blog_settings_form' => array(
						'type'   => 'form',
						'parent' => 'jet_blog_settings',
						'action' => add_query_arg(
							array( 'page' => $this->key, 'action' => 'save-settings' ),
							esc_url( admin_url( 'admin.php' ) )
						),
					),
				)
			);

			$this->builder->register_settings(
				array(
					'settings_top' => array(
						'type'   => 'settings',
						'parent' => 'jet_blog_settings_form',
					),
					'settings_bottom' => array(
						'type'   => 'settings',
						'parent' => 'jet_blog_settings_form',
					),
				)
			);

			$this->builder->register_control(
				array(
					'youtube_api_key' => array(
						'type'        => 'text',
						'id'          => 'youtube_api_key',
						'name'        => 'youtube_api_key',
						'parent'      => 'settings_top',
						'value'       => $this->get( 'youtube_api_key' ),
						'title'       => esc_html__( 'YouTube API Key:', 'jet-blog' ),
						'placeholder' => esc_html__( 'YouTube API key', 'jet-blog' ),
						'description' => sprintf(
							esc_html__( 'Create own API key here %s', 'jet-blog' ),
							make_clickable( 'https://console.developers.google.com/apis/dashboard' )
						)
					),
				)
			);

			$this->builder->register_control(
				array(
					'allow_filter_for' => array(
						'type'        => 'select',
						'multiple'    => true,
						'options'     => jet_blog_tools()->get_post_types(),
						'id'          => 'allow_filter_for',
						'name'        => 'allow_filter_for',
						'parent'      => 'settings_top',
						'value'       => $this->get( 'allow_filter_for', 'post' ),
						'title'       => __( 'Smart Posts List: allow filters for post types', 'jet-blog' ),
						'description' => __( 'Select post types supports Filter by Terms feature', 'jet-blog' ),
					),
				)
			);

			$this->builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'settings_bottom',
						'class'  => 'cx-control dialog-save',
						'html'   => '<button type="submit" class="button button-primary">' . esc_html__( 'Save', 'jet-blog' ) . '</button>',
					),
				)
			);
		}

		/**
		 * Render settings page
		 *
		 * @return void
		 */
		public function render_page() {

			echo '<div class="jet-blog-settings-page">';
				$this->builder->render();
			echo '</div>';

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

/**
 * Returns instance of Jet_Blog_Settings
 *
 * @return object
 */
function jet_blog_settings() {
	return Jet_Blog_Settings::get_instance();
}

jet_blog_settings()->init();
