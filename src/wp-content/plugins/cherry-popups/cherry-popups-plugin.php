<?php
/**
 * Plugin Name: Cherry PopUps
 * Plugin URI:  https://jetimpex.com/wordpress/
 * Description: A plugin for WordPress.
 * Version:     1.1.8
 * Author:      Jetimpex
 * Author URI:  https://jetimpex.com/wordpress/
 * Text Domain: cherry-popups
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 *
 * @package Cherry_Popups
 * @author  Cherry Team
 * @version 1.1.6
 * @license GPL-3.0+
 * @copyright  2002-2016, Cherry Team
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Cherry_Popups` doesn't exists yet.
if ( ! class_exists( 'Cherry_Popups' ) ) {

	/**
	 * Sets up and initializes the Cherry_Popups plugin.
	 */
	class Cherry_Popups {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since 1.0.0
		 * @access private
		 * @var   object
		 */
		private $core = null;

		/**
		 * Cherry utility init
		 *
		 * @var null
		 */
		public $cherry_utility = null;

		/**
		 * Dynamic_css module instance.
		 *
		 * @var null
		 */
		public $dynamic_css = null;

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Default options
		 *
		 * @var array
		 */
		public $default_options = array(
			'enable-popups'            => 'true',
			'mobile-enable-popups'     => 'true',
			'enable-logged-users'      => 'true',
			'default-open-page-popup'  => 'disable',
			'open-page-popup-display'  => array(),
			'default-close-page-popup' => 'disable',
			'close-page-popup-display' => array(),
		);

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Set the constants needed by the plugin.
			$this->constants();

			// Load the functions files.
			$this->includes();

			// Load the installer core.
			add_action( 'after_setup_theme', require( trailingslashit( dirname( __FILE__ ) ) . 'cherry-framework/setup.php' ), 0 );

			// Load the core functions/classes required by the rest of the plugin.
			add_action( 'after_setup_theme', array( $this, 'get_core' ), 1 );

			// Laad the modules.
			add_action( 'after_setup_theme', array( 'Cherry_Core', 'load_all_modules' ), 2 );

			// Initialization of modules.
			add_action( 'after_setup_theme', array( $this, 'init_modules' ), 3 );

			// Internationalize the text strings used.
			add_action( 'plugins_loaded', array( $this, 'lang' ), 1 );

			// Load the admin files.
			add_action( 'plugins_loaded', array( $this, 'admin' ), 2 );

			// Register public assets.
			add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 10 );

			// Load public-facing StyleSheets.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 11 );

			// Load public-facing JavaScripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 12 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

			// Set default wp solial login icon set
			apply_filters( 'pre_option_wsl_settings_social_icon_set', 'none' );
		}

		/**
		 * Defines constants for the plugin.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function constants() {

			/**
			 * Set the version number of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_POPUPS_VERSION', '1.1.8' );

			/**
			 * Set constant name for the post type name.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_POPUPS_NAME', 'cherry_popups' );

			/**
			 * Set the slug of the plugin.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_POPUPS_SLUG', basename( dirname( __FILE__ ) ) );

			/**
			 * Set the name for the 'meta_key' value in the 'wp_postmeta' table.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_POPUPS_POSTMETA', 'cherry-popups' );

			/**
			 * Set constant path to the plugin directory.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_POPUPS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );

			/**
			 * Set constant path to the plugin URI.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_POPUPS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );

			/**
			 * Set constant DB option field.
			 *
			 * @since 1.0.0
			 */
			define( 'CHERRY_OPTIONS_NAME', 'cherry_popups_options' );
		}

		/**
		 * Loads files from the '/includes' folder.
		 *
		 * @since 1.0.0
		 */
		function includes() {
			require_once( trailingslashit( CHERRY_POPUPS_DIR ) . 'includes/public/class-popups-registration.php' );
			require_once( trailingslashit( CHERRY_POPUPS_DIR ) . 'includes/public/class-popups-data.php' );
			require_once( trailingslashit( CHERRY_POPUPS_DIR ) . 'includes/public/class-popups-init.php' );
		}

		/**
		 * Loads the core functions. These files are needed before loading anything else in the
		 * plugin because they have required functions for use.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public function get_core() {

			/**
			 * Fires before loads the plugin's core.
			 *
			 * @since 1.0.0
			 */
			do_action( 'cherry_popups_core_before' );

			global $chery_core_version;

			if ( null !== $this->core ) {
				return $this->core;
			}

			if ( 0 < sizeof( $chery_core_version ) ) {
				$core_paths = array_values( $chery_core_version );
				require_once( $core_paths[0] );
			} else {
				die( 'Class Cherry_Core not found' );
			}

			$this->core = new Cherry_Core( array(
				'base_dir' => CHERRY_POPUPS_DIR . 'cherry-framework',
				'base_url' => CHERRY_POPUPS_URI . 'cherry-framework',
				'modules'  => array(
					'cherry-js-core' => array(
						'autoload' => true,
					),
					'cherry-toolkit' => array(
						'autoload' => false,
					),
					'cherry-ui-elements' => array(
						'autoload' => false,
					),
					'cherry-interface-builder' => array(
						'autoload' => false,
					),
					'cherry-utility' => array(
						'autoload' => false,
					),
					'cherry-handler' => array(
						'autoload' => false,
					),
					'cherry-post-meta' => array(
						'autoload' => false,
					),
					'cherry-dynamic-css' => array(
						'autoload' => false,
					),
				),
			) );

			return $this->core;
		}

		/**
		 * Run initialization of modules.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function init_modules() {
			if ( is_admin() ) {
				$this->get_core()->init_module( 'cherry-interface-builder', array() );
			}

			$this->get_core()->init_module( 'cherry-utility' );
			$this->cherry_utility = $this->get_core()->modules['cherry-utility']->utility;

			$this->dynamic_css = $this->get_core()->init_module( 'cherry-dynamic-css', array() );
		}

		/**
		 * Loads admin files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function admin() {
			if ( is_admin() ) {
				require_once( CHERRY_POPUPS_DIR . 'includes/admin/class-cherry-popups-admin.php' );
				require_once( CHERRY_POPUPS_DIR . 'includes/admin/class-cherry-popups-meta-boxes.php' );
			}
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain( 'cherry-popups', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Register assets.
		 *
		 * @since 1.0.0
		 */
		public function register_assets() {
			// Register stylesheets.
			wp_register_style( 'cherry-popups-font-awesome', esc_url( CHERRY_POPUPS_URI . 'assets/css/font-awesome.min.css' ), array(), '4.7.0', 'all' );
			wp_register_style( 'cherry-popups-styles', esc_url( CHERRY_POPUPS_URI . 'assets/css/cherry-popups-styles.css' ), array(), CHERRY_POPUPS_VERSION, 'all' );

			// Register JavaScripts.
			wp_register_script( 'cherry-popups-plugin', trailingslashit( CHERRY_POPUPS_URI ) . 'assets/js/cherry-popups-plugin.js', array( 'jquery', 'cherry-handler-js' ), CHERRY_POPUPS_VERSION, true );
			wp_register_script( 'cherry-popups-scripts', trailingslashit( CHERRY_POPUPS_URI ) . 'assets/js/cherry-popups-scripts.js', array( 'jquery', 'cherry-popups-plugin' ), CHERRY_POPUPS_VERSION, true );
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {
			wp_enqueue_style( 'cherry-popups-font-awesome' );
			wp_enqueue_style( 'cherry-popups-styles' );
		}

		/**
		 * Enqueue public-facing JavaScripts.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_scripts() {
			wp_enqueue_script( 'cherry-popups-scripts' );
		}

		/**
		 * Option field exist check
		 *
		 * @since 1.0.0
		 */
		public static function is_db_option_exist( $option_name ) {

			( false == get_option( $option_name ) ) ? $is_exist = false : $is_exist = true;

			return $is_exist;
		}

		/**
		 *
		 * Save options to DB
		 *
		 * @since 1.0.0
		 */
		public function save_options( $option_name, $options ) {

			$options = array_merge( $this->default_options, $options );
			update_option( $option_name, $options );
		}

		/**
		 * Get option value
		 *
		 * @since 1.0.0
		 */
		public function get_option( $option_name, $option_default = false ) {

			$cached = wp_cache_get( $option_name, CHERRY_OPTIONS_NAME );

			if ( $cached ) {
				return $cached;
			}

			if ( self::is_db_option_exist( CHERRY_OPTIONS_NAME ) ) {
				$current_options = get_option( CHERRY_OPTIONS_NAME );

				if ( array_key_exists( $option_name, $current_options ) ) {
					wp_cache_set( $option_name, $current_options[ $option_name ], CHERRY_OPTIONS_NAME );

					return $current_options[ $option_name ];
				}
			} else {
				$default_options = $this->default_options;

				if ( array_key_exists( $option_name, $default_options ) ) {
					wp_cache_set( $option_name, $default_options[ $option_name ], CHERRY_OPTIONS_NAME );

					return $default_options[ $option_name ];
				}
			}

			wp_cache_set( $option_name, $option_default, CHERRY_OPTIONS_NAME );

			return $option_default;
		}

		/**
		 * Returns path to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_path( $path = null ) {

			if ( ! $this->plugin_path ) {
				$this->plugin_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			}

			return $this->plugin_path . $path;
		}

		/**
		 * On plugin activation.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function activation() {
			if ( ! self::is_db_option_exist( CHERRY_OPTIONS_NAME ) ) {
				$this->save_options( CHERRY_OPTIONS_NAME, $this->default_options );
			}

			if ( ! self::is_db_option_exist( CHERRY_OPTIONS_NAME . '_default' ) ) {
				$this->save_options( CHERRY_OPTIONS_NAME . '_default', $this->default_options );
			}

			Cherry_Popups_Registration::register();

			flush_rewrite_rules();
		}

		/**
		 * On plugin deactivation.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function deactivation() {}

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

if ( ! function_exists( 'cherry_popups' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function cherry_popups() {
		return Cherry_Popups::get_instance();
	}
}

cherry_popups();
