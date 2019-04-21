<?php
/**
 * Plugin Name: Jet Popup
 * Plugin URI:  http://jetpopup.zemez.io/
 * Description: The advanced plugin for creating popups with Elementor
 * Version:     1.2.6.1
 * Author:      Zemez
 * Author URI:  https://zemez.io/wordpress/
 * Text Domain: jet-popup
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 *
 * @package jet-popup
 * @author  Zemez
 * @version 1.0.0
 * @license GPL-2.0+
 * @copyright  2018, Zemez
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Popup` doesn't exists yet.
if ( ! class_exists( 'Jet_Popup' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Popup {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Plugin version
		 *
		 * @var string
		 */

		private $version = '1.2.6.1';

		/**
		 * Holder for base plugin URL
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_url = null;

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * Framework component
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    object
		 */
		public $framework = null;

		/**
		 * [$assets description]
		 * @var [type]
		 */
		public $assets = null;

		/**
		 * [$post_type description]
		 * @var [type]
		 */
		public $post_type = null;

		/**
		 * [$settings description]
		 * @var null
		 */
		public $settings = null;

		/**
		 * [$export_import description]
		 * @var null
		 */
		public $export_import = null;

		/**
		 * [$conditions description]
		 * @var [type]
		 */
		public $conditions = null;

		/**
		 * [$extensions description]
		 * @var null
		 */
		public $extensions = null;

		/**
		 * [$integration description]
		 * @var null
		 */
		public $integration = null;

		/**
		 * [$generator description]
		 * @var null
		 */
		public $generator = null;

		/**
		 * [$ajax_handlers description]
		 * @var null
		 */
		public $ajax_handlers = null;

		/**
		 * [$elementor_finder description]
		 * @var null
		 */
		public $elementor_finder = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Load framework
			add_action( 'after_setup_theme', array( $this, 'framework_loader' ), -20 );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );

			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

			// Check if Elementor installed and activated
			if ( ! did_action( 'elementor/loaded' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
				return;
			}

			// Plugin row meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

			// Register activation  hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );

			// Register deactivation hook.
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
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * [elementor description]
		 * @return [type] [description]
		 */
		public function elementor() {
			return \Elementor\Plugin::$instance;
		}

		/**
		 * Load framework modules
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public function framework_loader() {
			require $this->plugin_path( 'framework/loader.php' );

			$this->framework = new Jet_Popup_CX_Loader(
				[
					$this->plugin_path( 'framework/interface-builder/cherry-x-interface-builder.php' ),
				]
			);
		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			if ( ! $this->has_elementor() ) {
				return;
			}

			$this->load_files();

			$this->assets = new Jet_Popup_Assets();

			$this->post_type = new Jet_Popup_Post_Type();

			$this->settings = new Jet_Popup_Settings();

			$this->export_import = new Jet_Export_Import();

			$this->conditions = new Jet_Popup_Conditions_Manager();

			$this->extensions = new Jet_Popup_Element_Extensions();

			$this->integration = new Jet_Popup_Integration();

			$this->generator = new Jet_Popup_Generator();

			$this->ajax_handlers = new Jet_Popup_Ajax_Handlers();

			$this->elementor_finder = new Jet_Elementor_Finder();

			if ( is_admin() ) {

				new Jet_Popup_Admin_Ajax_Handlers();

				require $this->plugin_path( 'includes/updater/class-jet-popup-plugin-update.php' );

				jet_popup_updater()->init( array(
					'version' => $this->get_version(),
					'slug'    => 'jet-popup',
				) );

			}

		}

		/**
		 * Load required files.
		 *
		 * @return void
		 */
		public function load_files() {
			require $this->plugin_path( 'includes/assets.php' );
			require $this->plugin_path( 'includes/admin-ajax-handlers.php' );
			require $this->plugin_path( 'includes/ajax-handlers.php' );
			require $this->plugin_path( 'includes/post-type.php' );
			require $this->plugin_path( 'includes/settings.php' );
			require $this->plugin_path( 'includes/export-import.php' );
			require $this->plugin_path( 'includes/utils.php' );
			require $this->plugin_path( 'includes/conditions/manager.php' );
			require $this->plugin_path( 'includes/extension.php' );
			require $this->plugin_path( 'includes/integration.php' );
			require $this->plugin_path( 'includes/generator.php' );
			require $this->plugin_path( 'includes/elementor-finder/elementor-finder.php' );
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
			load_plugin_textdomain( 'jet-popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'jet-popup/template-path', 'jet-popup/' );
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
		 * Add plugin changelog link.
		 *
		 * @param array  $plugin_meta
		 * @param string $plugin_file
		 *
		 * @return array
		 */
		public function plugin_row_meta( $plugin_meta, $plugin_file ) {

			if ( plugin_basename( __FILE__ ) === $plugin_file ) {
				$plugin_meta['changelog'] = sprintf(
					'<a href="http://documentation.zemez.io/wordpress/index.php?project=jetpopup&lang=en&section=jetpopup-changelog" target="_blank">%s</a>',
					esc_html__( 'Changelog', 'jet-popup' )
				);
			}

			return $plugin_meta;
		}

		/**
		 * [admin_notice_missing_main_plugin description]
		 * @return [type] [description]
		 */
		public function admin_notice_missing_main_plugin() {

			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

			$elementor_link = sprintf(
				'<a href="%1$s">%2$s</a>',
				admin_url() . 'plugin-install.php?s=elementor&tab=search&type=term',
				'<strong>' . esc_html__( 'Elementor', 'jet-popup' ) . '</strong>'
			);

			$message = sprintf(
				esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'jet-popup' ),
				'<strong>' . esc_html__( 'Jet Popup', 'jet-popup' ) . '</strong>',
				$elementor_link
			);

			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {

			//Register jet-popup post type on activation hook
			require $this->plugin_path( 'includes/post-type.php' );

			Jet_Popup_Post_Type::register_post_type();

			flush_rewrite_rules();
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

if ( ! function_exists( 'jet_popup' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_popup() {
		return Jet_Popup::get_instance();
	}
}

jet_popup();
