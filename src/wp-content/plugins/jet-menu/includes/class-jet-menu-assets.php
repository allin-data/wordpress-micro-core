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

if ( ! class_exists( 'Jet_Menu_Assets' ) ) {

	/**
	 * Define Jet_Menu_Assets class
	 */
	class Jet_Menu_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ), 99 );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );
			add_action( 'admin_footer', array( $this, 'admin_templates' ) );

			// Register public assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_public_assets' ) );

			// Enqueue public assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ), 10 );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_elementor_widget_scripts' ) );
		}

		/**
		 * Load admin assets
		 *
		 * @param  string $hook Current page hook.
		 * @return void
		 */
		public function admin_assets( $hook ) {

			wp_register_script(
				'jet-sticky-sidebar',
				jet_menu()->plugin_url( 'assets/admin/js/jquery.sticky-sidebar.min.js' ),
				array( 'jquery' ),
				'3.2.0',
				true
			);

			wp_register_script(
				'jet-menu-admin',
				jet_menu()->plugin_url( 'assets/admin/js/admin.js' ),
				array( 'jet-sticky-sidebar', 'wp-util' ),
				jet_menu()->get_version(),
				true
			);

			wp_localize_script( 'jet-menu-admin', 'jetMenuAdminSettings', apply_filters(
				'jet-menu/assets/admin/localize',
				array(
					'tabs'    => jet_menu_settings_item()->get_tabs(),
					'strings' => array(
						'leaveEditor' => esc_html__( 'Do you want to leave the editor? Changes you made may not be saved.', 'jet-menu' ),
						'saveLabel' => esc_html__( 'Save', 'jet-menu' ),
						'triggerLabel' => esc_html__( 'JetMenu', 'jet-menu' ),
					),
					'optionPageMessages' => array(
						'saveMessage'         => esc_html__( 'Options have been saved', 'jet-menu' ),
						'restoreMessage'      => esc_html__( 'Settings have been restored, page will be reloaded', 'jet-menu' ),
						'emptyImportFile'     => esc_html__( 'Please select options file to import.', 'jet-menu' ),
						'incorrectImportFile' => esc_html__( 'Options file must be only in .json format.', 'jet-menu' ),
						'redirectUrl'         => menu_page_url( 'jet-menu-options', false ),
						'resetMessage'        => esc_html__( 'All menu options will be reseted to defaults. Please export current options to prevent data lost. Are you sure you want to continue?', 'jet-menu' ),
					),
					'importUrl' => add_query_arg( array( 'jet-action' => 'import-options' ), esc_url( admin_url( 'admin.php' ) ) ),
					'resetUrl'  => add_query_arg( array( 'jet-action' => 'reset-options' ), esc_url( admin_url( 'admin.php' ) ) ),
				)
			) );

			wp_register_style(
				'jet-menu-admin',
				jet_menu()->plugin_url( 'assets/admin/css/admin.css' ),
				array(),
				jet_menu()->get_version()
			);

			wp_register_style(
				'font-awesome',
				jet_menu()->plugin_url( 'assets/public/css/font-awesome.min.css' ),
				array(),
				'4.7.0'
			);

			wp_enqueue_script( 'jet-menu-admin' );
			wp_enqueue_style( 'jet-menu-admin' );
			wp_enqueue_style( 'font-awesome' );
		}

		/**
		 * Load editor styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			if ( ! isset( $_REQUEST['context'] ) || 'jet-menu' !== $_REQUEST['context'] ) {
				return;
			}

			wp_enqueue_style(
				'jet-menu-editor',
				jet_menu()->plugin_url( 'assets/admin/css/editor.css' ),
				array(),
				jet_menu()->get_version()
			);

		}

		/**
		 * Load public assets
		 *
		 * @param  string $hook Current page hook.
		 * @return void
		 */
		public function register_public_assets() {

			wp_register_style(
				'font-awesome',
				jet_menu()->plugin_url( 'assets/public/css/font-awesome.min.css' ),
				array(),
				'4.7.0'
			);

			wp_register_style(
				'jet-menu-public',
				jet_menu()->plugin_url( 'assets/public/css/public.css' ),
				array( 'font-awesome' ),
				jet_menu()->get_version()
			);

			wp_register_script(
				'jet-menu-plugin',
				jet_menu()->plugin_url( 'assets/public/js/jet-menu-plugin.js' ),
				array( 'jquery' ),
				jet_menu()->get_version(),
				true
			);

			wp_register_script(
				'jet-menu-public',
				jet_menu()->plugin_url( 'assets/public/js/jet-menu-public-script.js' ),
				array( 'jquery', 'jet-menu-plugin' ),
				jet_menu()->get_version(),
				true
			);

			$default_mobile_breakpoint = jet_menu_option_page()->get_default_mobile_breakpoint();

			wp_localize_script( 'jet-menu-public', 'jetMenuPublicSettings', apply_filters(
				'jet-menu/assets/public/localize',
				array(
					'menuSettings' => array(
						'jetMenuRollUp'            => jet_menu_option_page()->get_option( 'jet-menu-roll-up', 'false' ),
						'jetMenuMouseleaveDelay'   => jet_menu_option_page()->get_option( 'jet-menu-mouseleave-delay', 500 ),
						'jetMenuMegaWidthType'     => jet_menu_option_page()->get_option( 'jet-mega-menu-width-type', 'container' ),
						'jetMenuMegaWidthSelector' => jet_menu_option_page()->get_option( 'jet-mega-menu-selector-width-type', '' ),
						'jetMenuMegaOpenSubType'   => jet_menu_option_page()->get_option( 'jet-menu-open-sub-type', 'hover' ),
						'jetMenuMobileBreakpoint'  => jet_menu_option_page()->get_option( 'jet-menu-mobile-breakpoint', $default_mobile_breakpoint ),
					),
				)
			) );
		}

		/**
		 * Enqueue public assets.
		 *
		 * @since 1.0.0
		 */
		public function enqueue_public_assets() {

			wp_enqueue_style( 'jet-menu-public' );
			wp_enqueue_script( 'jet-menu-public' );
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_elementor_widget_scripts() {
			wp_enqueue_script(
				'jet-menu-widgets-scripts',
				jet_menu()->plugin_url( 'assets/public/js/jet-menu-widgets-scripts.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_menu()->get_version(),
				true
			);
		}

		/**
		 * Print admin templates
		 *
		 * @return void
		 */
		public function admin_templates() {

			$screen = get_current_screen();

			if ( 'nav-menus' !== $screen->base ) {
				return;
			}

			$templates = array(
				'menu-trigger'  => 'admin/html/menu-trigger.html',
				'popup-wrapper' => 'admin/html/popup-wrapper.html',
				'popup-tabs'    => 'admin/html/popup-tabs.html',
				'editor-frame'  => 'admin/html/editor-frame.html',
			);

			$this->print_templates_array( $templates );

		}

		/**
		 * Print templates array
		 *
		 * @param  array  $templates List of templates to print.
		 * @return [type]            [description]
		 */
		public function print_templates_array( $templates = array() ) {

			if ( empty( $templates ) ) {
				return;
			}

			foreach ( $templates as $id => $file ) {

				$file = jet_menu()->get_template( $file );

				if ( ! file_exists( $file ) ) {
					continue;
				}

				ob_start();
				include $file;
				$content = ob_get_clean();

				printf( '<script type="text/html" id="tmpl-%1$s">%2$s</script>', $id, $content );

			}

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
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
 * Returns instance of Jet_Menu_Assets
 *
 * @return object
 */
function jet_menu_assets() {
	return Jet_Menu_Assets::get_instance();
}
