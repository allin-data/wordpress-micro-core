<?php
/**
 * Sets up the admin functionality for the plugin.
 *
 * @package    Cherry_Popups
 * @subpackage Admin
 * @author     Cherry Team
 * @license    GPL-3.0+
 * @copyright  2002-2016, Cherry Team
 */

// If class `Cherry_Popups_Admin` doesn't exists yet.
if ( ! class_exists( 'Cherry_Popups_Admin' ) ) {

	/**
	 * Cherry_Popups_Admin class.
	 */
	class Cherry_Popups_Admin {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var object
		 */
		private static $instance = null;

		/**
		 * Class constructor.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Include libraries from the `includes/admin`
			$this->includes();

			// Load the admin menu.
			add_action( 'admin_menu', array( $this, 'menu' ) );

			// Load admin stylesheets.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			// Load admin JavaScripts.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Initialization of modules.
			add_action( 'after_setup_theme', array( $this, 'init_modules' ), 3 );
		}

		/**
		 * Include libraries from the `includes/admin`.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function includes() {

			// Include plugin pages.
			require_once( trailingslashit( CHERRY_POPUPS_DIR ) . 'includes/admin/pages/class-plugin-options-page.php' );
		}

		/**
		 * Run initialization of modules.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function init_modules() {
			$sys_messages = array(
				'invalid_base_data' => esc_html__( 'Unable to process the request without nonche or server error', 'cherry-popups' ),
				'no_right'          => esc_html__( 'No right for this action', 'cherry-popups' ),
				'invalid_nonce'     => esc_html__( 'Stop CHEATING!!!', 'cherry-popups' ),
				'access_is_allowed' => '',
				'wait_processing'   => esc_html__( 'Please wait, processing the previous request' ),
			);

			$options_ajax_handler = cherry_popups()->get_core()->init_module(
				'cherry-handler',
				array(
					'id'           => 'cherry_save_options_ajax',
					'action'       => 'cherry_save_options_ajax',
					'capability'   => 'manage_options',
					'callback'     => array( $this , 'cherry_save_options' ),
					'sys_messages' => $sys_messages,
				)
			);

			$options_ajax_handler = cherry_popups()->get_core()->init_module(
				'cherry-handler',
				array(
					'id'           => 'cherry_restore_options_ajax',
					'action'       => 'cherry_restore_options_ajax',
					'capability'   => 'manage_options',
					'callback'     => array( $this , 'cherry_restore_options' ),
					'sys_messages' => $sys_messages,
				)
			);
		}

		/**
		 * Register the admin menu.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function menu() {
			add_submenu_page(
				'edit.php?post_type=cherry_popups',
				esc_html__( 'Popups Options', 'cherry-popups' ),
				esc_html__( 'Settings', 'cherry-popups' ),
				'edit_theme_options',
				'cherry-popups-options',
				array( 'Popups_Options_Page', 'get_instance' )
			);
		}

		/**
		 * Save options
		 *
		 * @since 1.0.0
		 */
		public function cherry_save_options() {

			if ( ! empty( $_REQUEST['data'] ) ) {
				$data = $_REQUEST['data'];
				cherry_popups()->save_options( CHERRY_OPTIONS_NAME, $data );
			}
		}

		/**
		 * Restore options
		 *
		 * @since 1.0.0
		 */
		public function cherry_restore_options() {
			$default_options = get_option( CHERRY_OPTIONS_NAME . '_default' );
			cherry_popups()->save_options( CHERRY_OPTIONS_NAME, $default_options );
		}

		/**
		 * Enqueue admin stylesheets.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string $hook The current admin page.
		 * @return void
		 */
		public function enqueue_styles( $hook ) {
			if ( Cherry_Popups_Admin::is_plugin_page() ) {
				wp_enqueue_style(
					'cherry-popups-admin-styles',
					esc_url( CHERRY_POPUPS_URI . 'assets/admin/css/cherry-popups-admin-styles.css' ),
					array(), CHERRY_POPUPS_VERSION,
					'all'
				);
			}
		}

		/**
		 * Enqueue admin JavaScripts.
		 *
		 * @since  1.0.0
		 * @access public
		 * @param  string $hook The current admin page.
		 * @return void
		 */
		public function enqueue_scripts( $hook ) {
			if ( Cherry_Popups_Admin::is_plugin_page() ) {
				wp_enqueue_script(
					'cherry-popups-admin-scripts',
					esc_url( CHERRY_POPUPS_URI . 'assets/admin/js/cherry-popups-admin-scripts.js' ),
					array( 'cherry-js-core', 'cherry-handler-js' ),
					CHERRY_POPUPS_VERSION,
					true
				);

				$options_page_settings = array(
					'save_message'    => esc_html__( 'Options have been saved', 'cherry-popups' ),
					'restore_message' => esc_html__( 'Settings have been restored, page will be reloaded', 'cherry-projects' ),
					'redirect_url'    => menu_page_url( 'cherry-popups-options', false ),
				);

				wp_localize_script( 'cherry-popups-admin-scripts', 'cherryPopupSettings', $options_page_settings );
			}
		}

		/**
		 * Check current plugin page.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return bool
		 */
		public static function is_plugin_page() {
			$screen = get_current_screen();

			return ( ! empty( $screen->post_type ) && false !== strpos( $screen->post_type, 'cherry_popups' ) ) ? true : false ;
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

if ( ! function_exists( 'cherry_popups_admin' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function cherry_popups_admin() {
		return Cherry_Popups_Admin::get_instance();
	}
}

cherry_popups_admin();
