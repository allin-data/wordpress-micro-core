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

if ( ! class_exists( 'Jet_Theme_Core_Settings' ) ) {

	/**
	 * Define Jet_Theme_Core_Settings class
	 */
	class Jet_Theme_Core_Settings {

		public  $option_slug  = 'jet_theme_core_settings';
		public  $page_slug    = 'jet-theme-core';
		private $settings     = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			if ( ! is_admin() ) {
				return;
			}

			add_filter( 'kava-extra/settings-page/is-enabled', '__return_false' );
			add_action( 'admin_enqueue_scripts', array( $this, 'init_builder' ), 0 );
			add_action( 'admin_notices', array( $this, 'saved_notice' ) );

		}

		/**
		 * Initialize page builder module if reqired
		 *
		 * @return [type] [description]
		 */
		public function init_builder() {

			if ( ! isset( $_REQUEST['page'] ) || $this->page_slug !== $_REQUEST['page'] ) {
				return;
			}

			if ( isset( $_REQUEST['tab'] ) && 'settings' !== $_REQUEST['tab'] ) {
				return;
			}

			$builder_data = jet_theme_core()->framework->get_included_module_data( 'cherry-x-interface-builder.php' );

			$this->builder = new CX_Interface_Builder(
				array(
					'path' => $builder_data['path'],
					'url'  => $builder_data['url'],
				)
			);

		}

		/**
		 * Show saved notice
		 *
		 * @return bool
		 */
		public function saved_notice() {

			if ( ! isset( $_REQUEST['page'] ) || $this->page_slug !== $_REQUEST['page'] ) {
				return false;
			}

			if ( ! isset( $_GET['core-settings-saved'] ) ) {
				return false;
			}

			$message = esc_html__( 'Settings saved', 'jet-theme-core' );

			printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', $message );

			return true;

		}

		/**
		 * Save settings
		 *
		 * @return void
		 */
		public function save( $data ) {

			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$current       = get_option( $this->option_slug, array() );
			$theme_options = array();

			if ( function_exists( 'kava_extra_settings' ) ) {
				$theme_options = kava_extra_settings()->get_controls_list( 'jet_theme_core_settings_form' );
			}

			unset( $data['action'] );

			foreach ( $data as $key => $value ) {

				if ( isset( $theme_options[ $key ] ) ) {
					$this->save_theme_option( $key, $value );
					continue;
				}

				$current[ $key ] = is_array( $value ) ? $value : esc_attr( $value );

			}

			update_option( $this->option_slug, $current );

			$redirect = add_query_arg(
				array( 'core-settings-saved' => true ),
				$this->get_settings_page_link()
			);

			wp_redirect( $redirect );
			die();

		}

		/**
		 * Save theme option added to plugin options list
		 *
		 * @return void
		 */
		public function save_theme_option( $name, $value ) {

			if ( ! function_exists( 'kava_extra_settings' ) ) {
				return;
			}

			kava_extra_settings()->save_key( $name, $value );

		}

		/**
		 * Update single option key in options array
		 *
		 * @return void
		 */
		public function save_key( $key, $value ) {

			$current = get_option( $this->option_slug, array() );
			$current[ $key ] = $value;
			update_option( $this->option_slug, $current );

		}

		/**
		 * Return settings page URL
		 *
		 * @return string
		 */
		public function get_settings_page_link() {

			return add_query_arg(
				array(
					'page' => $this->page_slug,
					'tab'  => 'settings',
				),
				esc_url( admin_url( 'admin.php' ) )
			);

		}

		public function get( $setting, $default = false ) {

			if ( null === $this->settings ) {
				$this->settings = get_option( $this->option_slug, array() );
			}

			return isset( $this->settings[ $setting ] ) ? $this->settings[ $setting ] : $default;

		}

		/**
		 * Render settings page
		 *
		 * @return void
		 */
		public function render_page() {

			$this->builder->register_form(
				array(
					'jet_theme_core_settings_form' => array(
						'type'   => 'form',
						'action' => add_query_arg(
							array(
								'jet_action' => 'settings',
								'handle'     => 'save_settings',
							),
							esc_url( admin_url( 'admin.php' ) )
						),
					),
				)
			);

			$this->builder->register_control(
				apply_filters(
					'jet-theme-core/settings/general-fields',
					array(
						'pro_relations' => array(
							'type'        => 'select',
							'id'          => 'pro_relations',
							'name'        => 'pro_relations',
							'parent'      => 'jet_theme_core_settings_form',
							'value'       => $this->get( 'pro_relations', 'show_both' ),
							'options'     => array(
								'jet_override'      => 'Jet Overrides',
								'pro_override'      => 'Pro Overrides',
								'show_both'         => 'Show Both, Jet Before Pro',
								'show_both_reverse' => 'Show Both, Pro Before Jet',
							),
							'title'       => esc_html__( 'Locations realtions', 'jet-theme-core' ),
							'description' => esc_html__( 'Define relations before Jet and Pro templates attached to the same locations', 'jet-theme-core' ),
						),
						'prevent_pro_locations' => array(
							'type'        => 'switcher',
							'parent'      => 'jet_theme_core_settings_form',
							'title'       => esc_html__( 'Prevent Pro locations registration', 'jet-theme-core' ),
							'description' => esc_html__( 'Prevent Elemntor Pro locations registration from JetThemeCore. Enable this if your headers/footers disappear when JetThemeCore is active', 'jet-theme-core' ),
							'value'       => $this->get( 'prevent_pro_locations' ),
						),
					)
				)
			);

			if ( function_exists( 'kava_extra_settings' ) ) {

				$this->builder->register_control(
					kava_extra_settings()->get_controls_list( 'jet_theme_core_settings_form' )
				);

			}

			/**
			 * Register theme-related options on this hook
			 */
			do_action( 'jet-theme-core/settings/theme-options', $this );

			$this->builder->register_html(
				array(
					'save_button' => array(
						'type'   => 'html',
						'parent' => 'jet_theme_core_settings_form',
						'class'  => 'cx-control dialog-save',
						'html'   => '<button type="submit" class="cx-button cx-button-primary-style">' . esc_html__( 'Save', 'jet-theme-core' ) . '</button>',
					),
				)
			);

			$this->builder->render();

		}

	}

}
