<?php
/**
 * Plugin Name: JetThemeCore
 * Plugin URI:  https://crocoblock.com/plugins/
 * Description: Most powerful plugin created to make building websites super easy
 * Version:     1.1.8
 * Author:      Zemez
 * Author URI:  https://crocoblock.com
 * Text Domain: jet-theme-core
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Theme_Core` doesn't exists yet.
if ( ! class_exists( 'Jet_Theme_Core' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Theme_Core {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * A reference to an instance of cherry framework core class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private $core = null;

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */
		private $version = '1.1.8';

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Plugin base name
		 *
		 * @var string
		 */
		public $plugin_name = null;

		/**
		 * Components
		 */
		public $framework;
		public $assets;
		public $settings;
		public $dashboard;
		public $templates;
		public $templates_manager;
		public $config;
		public $locations;
		public $structures;
		public $conditions;
		public $api;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			$this->plugin_name = plugin_basename( __FILE__ );

			// Load framework
			add_action( 'after_setup_theme', array( $this, 'framework_loader' ), -20 );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );
			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Returns plugin version
		 *
		 * @return string
		 */
		public function get_version() {
			return $this->version;
		}

		/**
		 * Load framework modules
		 *
		 * @return [type] [description]
		 */
		public function framework_loader() {

			require $this->plugin_path( 'framework/loader.php' );

			$this->framework = new Jet_Theme_Core_CX_Loader(
				array(
					$this->plugin_path( 'framework/interface-builder/cherry-x-interface-builder.php' ),
				)
			);

		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			$this->load_files();

			$this->config            = new Jet_Theme_Core_Config();
			$this->assets            = new Jet_Theme_Core_Assets();
			$this->api               = new Jet_Theme_Core_API();
			$this->settings          = new Jet_Theme_Core_Settings();
			$this->templates         = new Jet_Theme_Core_Templates_Post_Type();
			$this->locations         = new Jet_Theme_Core_Locations();
			$this->structures        = new Jet_Theme_Core_Structures();
			$this->conditions        = new Jet_Theme_Core_Conditions_Manager();

			new Jet_Theme_Core_Elementor_Integration();

			if ( is_admin() ) {

				$this->dashboard         = new Jet_Theme_Core_Dashboard();
				$this->templates_manager = new Jet_Theme_Core_Templates_Manager();

				new Jet_Theme_Core_Ajax_Handlers();

				require $this->plugin_path( 'includes/update.php' );

				new Jet_Theme_Core_Update();

			}

			do_action( 'jet-theme-core/init', $this );

		}

		/**
		 * Load required files
		 *
		 * @return void
		 */
		public function load_files() {

			// Global
			require $this->plugin_path( 'includes/assets.php' );
			require $this->plugin_path( 'includes/settings.php' );
			require $this->plugin_path( 'includes/config.php' );
			require $this->plugin_path( 'includes/api.php' );
			require $this->plugin_path( 'includes/ajax-handlers.php' );
			require $this->plugin_path( 'includes/elementor-integration.php' );
			require $this->plugin_path( 'includes/utils.php' );
			require $this->plugin_path( 'includes/locations.php' );

			// Dashboard
			require $this->plugin_path( 'includes/dashboard/manager.php' );

			// Templates
			require $this->plugin_path( 'includes/templates/post-type.php' );
			require $this->plugin_path( 'includes/templates/manager.php' );

			// Structures
			require $this->plugin_path( 'includes/structures/manager.php' );

			// Conditions
			require $this->plugin_path( 'includes/conditions/manager.php' );

		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor_pro() {
			return defined( 'ELEMENTOR_PRO_VERSION' );
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
		 * Returns url to file or dir inside plugin folder
		 *
		 * @param  string $path Path inside plugin dir.
		 * @return string
		 */
		public function plugin_url( $path = null ) {

			if ( ! $this->plugin_url ) {
				$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
			}

			return $this->plugin_url . $path;
		}

		/**
		 * Loads the translation files.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function lang() {
			load_plugin_textdomain( 'jet-theme-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'jet-theme-core/template-path', 'jet-theme-core/' );
		}

		/**
		 * Returns path to template file.
		 *
		 * @return string|bool
		 */
		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = $this->plugin_path( 'templates/' . $name );
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {
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

if ( ! function_exists( 'jet_theme_core' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_theme_core() {
		return Jet_Theme_Core::get_instance();
	}
}

jet_theme_core();
