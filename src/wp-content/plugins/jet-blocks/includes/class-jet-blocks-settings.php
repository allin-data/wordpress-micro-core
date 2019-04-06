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

if ( ! class_exists( 'Jet_Blocks_Settings' ) ) {

	/**
	 * Define Jet_Blocks_Settings class
	 */
	class Jet_Blocks_Settings {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * [$key description]
		 * @var string
		 */
		public $key = 'jet-blocks-settings';

		/**
		 * [$builder description]
		 * @var null
		 */
		public $builder  = null;

		/**
		 * [$settings description]
		 * @var null
		 */
		public $settings = null;

		/**
		 * Available Widgets array
		 *
		 * @var array
		 */
		public $avaliable_widgets = array();

		/**
		 * Default Available Extensions
		 *
		 * @var array
		 */
		public $default_avaliable_ext = array(
			'sticky_section' => 'true',
			'column_order'   => 'true',
		);

		/**
		 * Init page
		 */
		public function init() {
			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );

			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );
			add_action( 'init', array( $this, 'save' ), 40 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );

			foreach ( glob( jet_blocks()->plugin_path( 'includes/widgets/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->avaliable_widgets[ $slug] = $data['name'];
			}
		}

		/**
		 * Initialize page builder module if required
		 *
		 * @return void
		 */
		public function init_builder() {

			if ( isset( $_REQUEST['page'] ) && $this->key === $_REQUEST['page'] ) {

				$builder_data = jet_blocks()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );
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

			$message = esc_html__( 'Settings saved', 'jet-blocks' );

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
				esc_html__( 'JetBlocks Settings', 'jet-blocks' ),
				esc_html__( 'JetBlocks Settings', 'jet-blocks' ),
				'manage_options',
				$this->key,
				array( $this, 'render_page' )
			);

		}

		/**
		 * Render settings page
		 *
		 * @return void
		 */
		public function render_page() {

			foreach ( $this->avaliable_widgets as $key => $value ) {
				$default_avaliable_widgets[ $key ] = 'true';
			}

			$this->builder->register_section(
				array(
					'jet_blocks_settings' => array(
						'type'   => 'section',
						'scroll' => false,
						'title'  => esc_html__( 'JetBlocks Settings', 'jet-blocks' ),
					),
				)
			);

			$this->builder->register_form(
				array(
					'jet_blocks_settings_form' => array(
						'type'   => 'form',
						'parent' => 'jet_blocks_settings',
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
						'parent' => 'jet_blocks_settings_form',
					),
					'settings_bottom' => array(
						'type'   => 'settings',
						'parent' => 'jet_blocks_settings_form',
					),
				)
			);

			$this->builder->register_component(
				array(
					'jet_blocks_tab_vertical' => array(
						'type'   => 'component-tab-vertical',
						'parent' => 'settings_top',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'avaliable_widgets_options' => array(
						'parent' => 'jet_blocks_tab_vertical',
						'title'  => esc_html__( 'Available Widgets', 'jet-blocks' ),
					),
				)
			);

			$this->builder->register_control(
				array(
					'avaliable_widgets' => array(
						'type'        => 'checkbox',
						'id'          => 'avaliable_widgets',
						'name'        => 'avaliable_widgets',
						'parent'      => 'avaliable_widgets_options',
						'value'       => $this->get( 'avaliable_widgets', $default_avaliable_widgets ),
						'options'     => $this->avaliable_widgets,
						'title'       => esc_html__( 'Available Widgets', 'jet-blocks' ),
						'description' => esc_html__( 'List of widgets that will be available when editing the page', 'jet-blocks' ),
						'class'       => 'jet_blocks_settings_form__checkbox-group'
					),
				)
			);

			$this->builder->register_settings(
				array(
					'avaliable_extensions_options' => array(
						'parent' => 'jet_blocks_tab_vertical',
						'title'  => esc_html__( 'Available Extensions', 'jet-blocks' ),
					),
				)
			);

			$this->builder->register_control(
				array(
					'avaliable_extensions' => array(
						'type'        => 'checkbox',
						'id'          => 'avaliable_extensions',
						'name'        => 'avaliable_extensions',
						'parent'      => 'avaliable_extensions_options',
						'value'       => $this->get( 'avaliable_extensions', $this->default_avaliable_ext ),
						'options'     => array(
							'sticky_section' => esc_html__( 'Sticky Section Extension', 'jet-blocks' ),
							'column_order'   => esc_html__( 'Column Order Extension', 'jet-blocks' ),
						),
						'title'       => esc_html__( 'Available Extensions', 'jet-blocks' ),
						'description' => esc_html__( 'List of Extension that will be available when editing the page', 'jet-blocks' ),
						'class'       => 'jet_blocks_settings_form__checkbox-group'
					),
				)
			);

			$this->builder->register_settings(
				array(
					'breadcrumbs_options' => array(
						'parent' => 'jet_blocks_tab_vertical',
						'title'  => esc_html__( 'Breadcrumbs Settings', 'jet-blocks' ),
					),
				)
			);

			$this->builder->register_html(
				array(
					'breadcrumbs_options_title' => array(
						'type'   => 'html',
						'parent' => 'breadcrumbs_options',
						'class'  => 'cx-control',
						'html'   => '<h3 class="cx-ui-kit__title">' . esc_html__( 'Taxonomy to show in breadcrumbs for content types', 'jet-blocks' ) . '</h3>',
					),
				)
			);

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			if ( is_array( $post_types ) && ! empty( $post_types ) ) {

				foreach ( $post_types as $post_type ) {
					$taxonomies = get_object_taxonomies( $post_type->name, 'objects' );

					if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
						$options = array( '' => esc_html__( 'None', 'jet-blocks' ) );

						foreach ( $taxonomies as $tax ) {
							if ( ! $tax->public ) {
								continue;
							}

							$options[ $tax->name ] = $tax->labels->singular_name;
						}

						$this->builder->register_control(
							array(
								'breadcrumbs_taxonomy_' . $post_type->name => array(
									'type'    => 'select',
									'id'      => 'breadcrumbs_taxonomy_' . $post_type->name,
									'name'    => 'breadcrumbs_taxonomy_' . $post_type->name,
									'parent'  => 'breadcrumbs_options',
									'value'   => $this->get( 'breadcrumbs_taxonomy_' . $post_type->name, ( 'post' === $post_type->name ) ? 'category' : '' ),
									'options' => $options,
									'title'   => $post_type->label,
								),
							)
						);
					}
				}
			}

			$this->builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'settings_bottom',
						'class'  => 'cx-control dialog-save',
						'html'   => '<button type="submit" class="button button-primary">' . esc_html__( 'Save', 'jet-blocks' ) . '</button>',
					),
				)
			);

			echo '<div class="jet-blocks-settings-page">';
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
 * Returns instance of Jet_Blocks_Settings
 *
 * @return object
 */
function jet_blocks_settings() {
	return Jet_Blocks_Settings::get_instance();
}

jet_blocks_settings()->init();
