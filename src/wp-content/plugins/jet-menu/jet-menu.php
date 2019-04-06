<?php
/**
 * Plugin Name: JetMenu
 * Plugin URI: http://jetmenu.zemez.io/
 * Description: A top-notch mega menu addon for Elementor. Use it to create a fully responsive mega menu with drop-down items, rich in content modules, and change your menu style according to your vision without any coding knowledge!
 * Version:     1.5.8.1
 * Author:      Zemez
 * Author URI:  https://zemez.io/wordpress/
 * Text Domain: jet-menu
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Menu` doesn't exists yet.
if ( ! class_exists( 'Jet_Menu' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Menu {

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
		private $version = '1.5.8.1';

		/**
		 * Plugin slug
		 *
		 * @var string
		 */
		public $plugin_slug = 'jet-menu';

		/**
		 * Holder for base plugin path
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    string
		 */
		private $plugin_path = null;

		/**
		 * UI elements instance
		 *
		 * @var object
		 */
		private $ui = null;

		/**
		 * Dynamic CSS module instance
		 *
		 * @var object
		 */
		private $dynamic_css = null;

		/**
		 * Customizer module instance
		 *
		 * @var object
		 */
		private $customizer = null;

		/**
		 * Dirname holder for plugins integration loader
		 *
		 * @var string
		 */
		private $dir = null;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Load the installer core.
			add_action( 'after_setup_theme', require( dirname( __FILE__ ) . '/cherry-framework/setup.php' ), 0 );

			// Load the core functions/classes required by the rest of the plugin.
			add_action( 'after_setup_theme', array( $this, 'get_core' ), 1 );
			// Load the modules.
			add_action( 'after_setup_theme', array( 'Cherry_Core', 'load_all_modules' ), 2 );

			// Internationalize the text strings used.
			add_action( 'init', array( $this, 'lang' ), -999 );
			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );
			// Plugin row meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
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
			do_action( 'jet-menu/core_before' );

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
				'base_dir' => $this->plugin_path( 'cherry-framework' ),
				'base_url' => $this->plugin_url( 'cherry-framework' ),
				'modules'  => array(
					'cherry-js-core' => array(
						'autoload' => true,
					),
					'cherry-ui-elements' => array(
						'autoload' => false,
					),
					'cherry-handler' => array(
						'autoload' => false,
					),
					'cherry-interface-builder' => array(
						'autoload' => false,
					),
					'cherry-utility' => array(
						'autoload' => true,
						'args'     => array(
							'meta_key' => array(
								'term_thumb' => 'cherry_terms_thumbnails'
							),
						)
					),
					'cherry-widget-factory' => array(
						'autoload' => true,
					),
					'cherry-term-meta' => array(
						'autoload' => false,
					),
					'cherry-post-meta' => array(
						'autoload' => false,
					),
					'cherry-dynamic-css' => array(
						'autoload' => false,
					),
					'cherry-customizer' => array(
						'autoload' => false,
					),
					'cherry-google-fonts-loader' => array(
						'autoload' => false,
					),
					'cherry5-insert-shortcode' => array(
						'autoload' => false,
					),
					'cherry5-assets-loader' => array(
						'autoload' => false,
					),
				),
			) );

			return $this->core;
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
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			$this->load_files();

			$this->dynamic_css = $this->get_core()->init_module( 'cherry-dynamic-css' );
			$this->customizer  = $this->get_core()->init_module( 'cherry-customizer', array( 'just_fonts' => true ) );

			$this->customizer->init_fonts();

			jet_menu_assets()->init();
			jet_menu_post_type()->init();
			jet_menu_css_file()->init();
			jet_menu_public_manager()->init();
			jet_menu_integration()->init();

			jet_menu_option_page();
			jet_menu_options_presets()->init();

			$this->include_integration_theme_file();
			$this->include_integration_plugin_file();

			if ( is_admin() ) {

				jet_menu_settings_item()->init();
				jet_menu_settings_nav()->init();

				add_action( 'admin_init', array( $this, 'init_ui' ) );

				require $this->plugin_path( 'includes/updater/class-jet-menu-plugin-update.php' );

				jet_menu_updater()->init( array(
					'version' => $this->get_version(),
					'slug'    => 'jet-menu',
				) );

				if ( ! $this->has_elementor() ) {
					$this->required_plugins_notice();
				}

			}

		}

		/**
		 * Initialize UI elements instance
		 *
		 * @return void
		 */
		public function init_ui() {

			global $pagenow;

			if ( 'nav-menus.php' !== $pagenow ) {
				return;
			}

			$this->ui = $this->get_core()->init_module( 'cherry-ui-elements' );
		}

		/**
		 * Return UI elements instance
		 *
		 * @return object
		 */
		public function ui() {
			return $this->ui;
		}

		/**
		 * Return dynamic CSS instance
		 *
		 * @return object
		 */
		public function dynamic_css() {
			return $this->dynamic_css;
		}

		/**
		 * Return customizer instance
		 *
		 * @return object
		 */
		public function customizer() {
			return $this->customizer;
		}

		/**
		 * Show recommended plugins notice.
		 *
		 * @return void
		 */
		public function required_plugins_notice() {
			require $this->plugin_path( 'includes/lib/class-tgm-plugin-activation.php' );
			add_action( 'tgmpa_register', array( $this, 'register_required_plugins' ) );
		}

		/**
		 * Register required plugins
		 *
		 * @return void
		 */
		public function register_required_plugins() {

			$plugins = array(
				array(
					'name'     => 'Elementor',
					'slug'     => 'elementor',
					'required' => true,
				),
			);

			$config = array(
				'id'           => 'jet-menu',
				'default_path' => '',
				'menu'         => 'tgmpa-install-plugins',
				'parent_slug'  => 'plugins.php',
				'capability'   => 'manage_options',
				'has_notices'  => true,
				'dismissable'  => true,
				'dismiss_msg'  => '',
				'is_automatic' => false,
				'strings'      => array(
					'notice_can_install_required'     => _n_noop(
						'JetMenu for Elementor requires the following plugin: %1$s.',
						'JetMenu for Elementor requires the following plugins: %1$s.',
						'jet-menu'
					),
					'notice_can_install_recommended'  => _n_noop(
						'JetMenu for Elementor recommends the following plugin: %1$s.',
						'JetMenu for Elementor recommends the following plugins: %1$s.',
						'jet-menu'
					),
				),
			);

			tgmpa( $plugins, $config );

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
		 * Returns utility instance
		 *
		 * @return object
		 */
		public function utility() {
			$utility = $this->get_core()->modules['cherry-utility'];

			return $utility->utility;
		}

		/**
		 * Load required files.
		 *
		 * @return void
		 */
		public function load_files() {
			require $this->plugin_path( 'includes/class-jet-menu-assets.php' );
			require $this->plugin_path( 'includes/class-jet-menu-dynamic-css.php' );
			require $this->plugin_path( 'includes/class-jet-menu-settings-item.php' );
			require $this->plugin_path( 'includes/class-jet-menu-settings-nav.php' );
			require $this->plugin_path( 'includes/class-jet-menu-post-type.php' );
			require $this->plugin_path( 'includes/class-jet-menu-tools.php' );
			require $this->plugin_path( 'includes/class-jet-menu-integration.php' );
			require $this->plugin_path( 'includes/walkers/class-jet-menu-main-walker.php' );
			require $this->plugin_path( 'includes/walkers/class-jet-menu-widget-walker.php' );
			require $this->plugin_path( 'includes/class-jet-menu-public-manager.php' );
			require $this->plugin_path( 'includes/class-jet-menu-options-page.php' );
			require $this->plugin_path( 'includes/class-jet-menu-options-presets.php' );
			require $this->plugin_path( 'includes/class-jet-menu-css-file.php' );
		}

		/**
		 * Include integration theme file
		 *
		 * @return void
		 */
		public function include_integration_theme_file() {

			$template = get_template();
			$disabled = jet_menu_option_page()->get_option( 'jet-menu-disable-integration-' . $template, 'false' );
			$disabled = filter_var( $disabled, FILTER_VALIDATE_BOOLEAN );

			if ( is_readable( $this->plugin_path( "integration/themes/{$template}/functions.php" ) ) && ! $disabled ) {
				require $this->plugin_path( "integration/themes/{$template}/functions.php" );
			}

		}

		/**
		 * Include plugin integrations file
		 *
		 * @return [type] [description]
		 */
		public function include_integration_plugin_file() {

			$active_plugins = get_option( 'active_plugins' );

			foreach ( glob( $this->plugin_path( 'integration/plugins/*' ) ) as $path ) {

				if ( ! is_dir( $path ) ) {
					continue;
				}

				$this->dir = basename( $path );

				$matched_plugins = array_filter( $active_plugins, array( $this, 'is_plugin_active' ) );

				if ( ! empty( $matched_plugins ) ) {
					require "{$path}/functions.php";
				}

			}

		}

		/**
		 * Callback to check if plugin is active
		 * @param  [type]  $plugin [description]
		 * @return boolean         [description]
		 */
		public function is_plugin_active( $plugin ) {
			return ( false !== strpos( $plugin, $this->dir . '/' ) );
		}

		/**
		 * Returns URL for current theme in theme-integration directory
		 *
		 * @param  string $file Path to file inside theme folder
		 * @return [type]       [description]
		 */
		public function get_theme_url( $file ) {

			$template = get_template();

			return $this->plugin_url( "integration/themes/{$template}/{$file}" );
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
			load_plugin_textdomain( 'jet-menu', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'jet-menu/template-path', 'jet-menu/' );
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

			$template = apply_filters( 'jet-menu/get-template/found', $template, $name );

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
					'<a href="http://documentation.zemez.io/wordpress/index.php?project=jetmenu&lang=en&section=jetmenu-changelog" target="_blank">%s</a>',
					esc_html__( 'Changelog', 'jet-menu' )
				);
			}

			return $plugin_meta;
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function activation() {
			require $this->plugin_path( 'includes/class-jet-menu-post-type.php' );
			jet_menu_post_type()->init();
			flush_rewrite_rules();
		}

		/**
		 * Do some stuff on plugin activation
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function deactivation() {
			flush_rewrite_rules();
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

if ( ! function_exists( 'jet_menu' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_menu() {
		return Jet_Menu::get_instance();
	}
}

jet_menu();
