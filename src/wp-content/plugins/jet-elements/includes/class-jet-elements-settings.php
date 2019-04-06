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

if ( ! class_exists( 'Jet_Elements_Settings' ) ) {

	/**
	 * Define Jet_Elements_Settings class
	 */
	class Jet_Elements_Settings {

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
		public $key = 'jet-elements-settings';

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
		 * Avaliable Widgets array
		 *
		 * @var array
		 */
		public $avaliable_widgets = [];

		/**
		 * [$default_avaliable_extensions description]
		 * @var [type]
		 */
		public $default_avaliable_extensions = [
			'section_parallax'  => 'true',
		];

		/**
		 * Init page
		 */
		public function init() {

			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );

			add_action( 'admin_menu', array( $this, 'register_page' ), 99 );
			add_action( 'init', array( $this, 'save' ), 40 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );

			foreach ( glob( jet_elements()->plugin_path( 'includes/addons/' ) . '*.php' ) as $file ) {
				$data = get_file_data( $file, array( 'class'=>'Class', 'name' => 'Name', 'slug'=>'Slug' ) );

				$slug = basename( $file, '.php' );
				$this->avaliable_widgets[ $slug ] = $data['name'];
			}
		}

		/**
		 * Initialize page builder module if reqired
		 *
		 * @return [type] [description]
		 */
		public function init_builder() {

			if ( isset( $_REQUEST['page'] ) && $this->key === $_REQUEST['page'] ) {

				$builder_data = jet_elements()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

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

			$message = esc_html__( 'Settings saved', 'jet-elements' );

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
				esc_html__( 'Jet Elements Settings', 'jet-elements' ),
				esc_html__( 'Jet Elements Settings', 'jet-elements' ),
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
					'jet_elements_settings' => array(
						'type'   => 'section',
						'scroll' => false,
						'title'  => esc_html__( 'Jet Elements Settings', 'jet-elements' ),
					),
				)
			);

			$this->builder->register_form(
				array(
					'jet_elements_settings_form' => array(
						'type'   => 'form',
						'parent' => 'jet_elements_settings',
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
						'parent' => 'jet_elements_settings_form',
					),
					'settings_bottom' => array(
						'type'   => 'settings',
						'parent' => 'jet_elements_settings_form',
					),
				)
			);

			$this->builder->register_component(
				array(
					'jet_elements_tab_vertical' => array(
						'type'   => 'component-tab-vertical',
						'parent' => 'settings_top',
					),
				)
			);

			$this->builder->register_settings(
				array(
					'general_tab' => array(
						'parent'      => 'jet_elements_tab_vertical',
						'title'       => esc_html__( 'General settings', 'jet-elements' ),
					),
					'google_map_options' => array(
						'parent'      => 'jet_elements_tab_vertical',
						'title'       => esc_html__( 'Google Map Options', 'jet-elements' ),
					),
					'mailing_options' => array(
						'parent'      => 'jet_elements_tab_vertical',
						'title'       => esc_html__( 'Mailing List Manager', 'jet-elements' ),
					),
					'instagram_options' => array(
						'parent'      => 'jet_elements_tab_vertical',
						'title'       => esc_html__( 'Instagram Options', 'jet-elements' ),
					),
					'weather_options' => array(
						'parent'      => 'jet_elements_tab_vertical',
						'title'       => esc_html__( 'Weather Options', 'jet-elements' ),
					),
					'avaliable_widgets_options' => array(
						'parent'      => 'jet_elements_tab_vertical',
						'title'       => esc_html__( 'Available Widgets', 'jet-elements' ),
					),
					'avaliable_extensions_options' => array(
						'parent'      => 'jet_elements_tab_vertical',
						'title'       => esc_html__( 'Available Extensions', 'jet-elements' ),
					),
				)
			);

			$controls = apply_filters( 'jet-elements/settings-page/controls-list',
				array(
					'svg_uploads' => array(
						'type'        => 'select',
						'id'          => 'svg_uploads',
						'name'        => 'svg_uploads',
						'parent'      => 'general_tab',
						'value'       => $this->get( 'svg_uploads', 'enabled' ),
						'options'     => array(
							'enabled'  => esc_html__( 'Enabled', 'jet-elements' ),
							'disabled' => esc_html__( 'Disabled', 'jet-elements' ),
						),
						'title'       => esc_html__( 'SVG images upload status:', 'jet-elements' ),
						'description' => esc_html__( 'Enable or disable SVG images uploading', 'jet-elements' ),
					),

					'jet_templates' => array(
						'type'        => 'select',
						'id'          => 'jet_templates',
						'name'        => 'jet_templates',
						'parent'      => 'general_tab',
						'value'       => $this->get( 'jet_templates', 'enabled' ),
						'options'     => array(
							'enabled'  => esc_html__( 'Enabled', 'jet-elements' ),
							'disabled' => esc_html__( 'Disabled', 'jet-elements' ),
						),
						'title'       => esc_html__( 'Use Jet Templates:', 'jet-elements' ),
						'description' => esc_html__( 'Add Jet page templates and blocks to Elementor templates library.', 'jet-elements' ),
					),

					'api_key' => array(
						'type'        => 'text',
						'id'          => 'api_key',
						'name'        => 'api_key',
						'parent'      => 'google_map_options',
						'value'       => $this->get( 'api_key' ),
						'title'       => esc_html__( 'API Key:', 'jet-elements' ),
						'placeholder' => esc_html__( 'API key', 'jet-elements' ),
						'description' => sprintf(
							esc_html__( 'Create own API key here %s', 'jet-elements' ),
							make_clickable( 'https://developers.google.com/maps/documentation/javascript/get-api-key' )
						)
					),

					'disable_api_js' => array(
						'type'        => 'checkbox',
						'id'          => 'disable_api_js',
						'name'        => 'disable_api_js',
						'parent'      => 'google_map_options',
						'value'       => $this->get( 'disable_api_js' ),
						'options'     => array( 'disable' => esc_html__( 'Disable', 'jet-elements' ), ),
						'title'       => esc_html__( 'Disable Maps API JS file:', 'jet-elements' ),
						'description' => esc_html__( 'Disable Google Maps API JS file, if it already included by another plugin or theme', 'jet-elements' ),
					),

					'mailchimp-api-key' => array(
						'type'         => 'text',
						'parent'       => 'mailing_options',
						'title'        => esc_html__( 'MailChimp API key', 'jet-elements' ),
						'placeholder'  => esc_html__( 'MailChimp API key', 'jet-elements' ),
						'description'  => sprintf( '%1$s <a href="http://kb.mailchimp.com/integrations/api-integrations/about-api-keys">%2$s</a>', esc_html__( 'Input your MailChimp API key', 'jet-elements' ), esc_html__( 'About API Keys', 'jet-elements' ) ),
						'value'        => $this->get( 'mailchimp-api-key' ),
						'class'        => '',
						'label'        => '',
					),

					'mailchimp-list-id' => array(
						'type'         => 'text',
						'parent'       => 'mailing_options',
						'title'        => esc_html__( 'MailChimp list ID', 'jet-elements' ),
						'placeholder'  => esc_html__( 'MailChimp list ID', 'jet-elements' ),
						'description'  => sprintf( '%1$s <a href="http://kb.mailchimp.com/lists/managing-subscribers/find-your-list-id">%2$s</a>', esc_html__( 'MailChimp list ID', 'jet-elements' ), esc_html__( 'list ID', 'jet-elements' ) ),
						'value'        => $this->get( 'mailchimp-list-id' ),
						'class'        => '',
						'label'        => '',
					),

					'mailchimp-double-opt-in' => array(
						'type'        => 'switcher',
						'parent'      => 'mailing_options',
						'title'       => esc_html__( 'Double opt-in', 'jet-elements' ),
						'description' => esc_html__( 'Send contacts an opt-in confirmation email when they subscribe to your list.', 'jet-elements' ),
						'value'       => $this->get( 'mailchimp-double-opt-in' ),
						'toggle'      => array(
							'true_toggle'  => 'On',
							'false_toggle' => 'Off',
						),
					),

					/*'mailchimp-get-list-merge-fields' => array(
						'type'    => 'button',
						'parent'  => 'mailing_options',
						'title'   => esc_html__( 'Sync list merge fields', 'jet-elements' ),
						'content' => esc_html__( 'Sync', 'jet-elements' ),
					),*/

					'insta_access_token' => array(
						'type'        => 'text',
						'id'          => 'insta_access_token',
						'name'        => 'insta_access_token',
						'parent'      => 'instagram_options',
						'value'       => $this->get( 'insta_access_token' ),
						'title'       => esc_html__( 'Access Token:', 'jet-elements' ),
						'placeholder' => esc_html__( 'Access Token', 'jet-elements' ),
						'description' => sprintf( esc_html__( 'Read more about how to get Instagram Access Token %1$s or %2$s', 'jet-elements' ),
							'<a target="_blank" href="https://elfsight.com/blog/2016/05/how-to-get-instagram-access-token/">' . esc_html__( 'here', 'jet-elements' ) . '</a>',
							'<a target="_blank" href="https://docs.oceanwp.org/article/487-how-to-get-instagram-access-token">' . esc_html__( 'here', 'jet-elements' ) . '</a>'
						),
					),

					'weather_api_key' => array(
						'type'        => 'text',
						'id'          => 'weather_api_key',
						'name'        => 'weather_api_key',
						'parent'      => 'weather_options',
						'value'       => $this->get( 'weather_api_key' ),
						'title'       => esc_html__( 'API Key:', 'jet-elements' ),
						'placeholder' => esc_html__( 'API key', 'jet-elements' ),
						'description' => sprintf(
							esc_html__( 'Create own APIXU Weather API key here %s', 'jet-elements' ),
							make_clickable( 'https://www.apixu.com/api.aspx' )
						)
					),

					'avaliable_widgets' => array(
						'type'        => 'checkbox',
						'id'          => 'avaliable_widgets',
						'name'        => 'avaliable_widgets',
						'parent'      => 'avaliable_widgets_options',
						'value'       => $this->get( 'avaliable_widgets', $default_avaliable_widgets ),
						'options'     => $this->avaliable_widgets,
						'title'       => esc_html__( 'Available Widgets', 'jet-elements' ),
						'description' => esc_html__( 'List of widgets that will be available when editing the page', 'jet-elements' ),
						'class'       => 'jet_elements_settings_form__checkbox-group'
					),

					'avaliable_extensions' => array(
						'type'        => 'checkbox',
						'id'          => 'avaliable_extensions',
						'name'        => 'avaliable_extensions',
						'parent'      => 'avaliable_extensions_options',
						'value'       => $this->get( 'avaliable_extensions', $this->default_avaliable_extensions ),
						'options'     => [
							'section_parallax'  => esc_html__( 'Section Parallax Extension', 'jet-elements' ),
						],
						'title'       => esc_html__( 'Avaliable Extensions', 'jet-tricks' ),
						'description' => esc_html__( 'List of Extension that will be available when editing the page', 'jet-tricks' ),
						'class'       => 'jet_elements_settings_form__checkbox-group'
					),
				)
			);

			$this->builder->register_control( $controls );

			$this->builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'settings_bottom',
						'class'  => 'cherry-control dialog-save',
						'html'   => '<button type="submit" class="button button-primary">' . esc_html__( 'Save', 'jet-elements' ) . '</button>',
					),
				)
			);

			echo '<div class="jet-elements-settings-page">';
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
 * Returns instance of Jet_Elements_Settings
 *
 * @return object
 */
function jet_elements_settings() {
	return Jet_Elements_Settings::get_instance();
}

jet_elements_settings()->init();
