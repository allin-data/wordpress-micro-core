<?php
/**
 * Plugin Name: JetBlocks For Elementor
 * Plugin URI:  http://jetblocks.zemez.io/
 * Description: The basic utilitary widgets for implementing additional functionality to headers, footers and special sections built with Elementor
 * Version:     1.1.5
 * Author:      Zemez
 * Author URI:  https://zemez.io/wordpress/
 * Text Domain: jet-blocks
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Jet_Blocks` doesn't exists yet.
if ( ! class_exists( 'Jet_Blocks' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Jet_Blocks {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

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

		private $version = '1.1.5';

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
		public $framework;

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
			// Plugin row meta
			add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );

			// Register activation and deactivation hook.
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );
		}

		/**
		 * Load framework modules
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		public function framework_loader() {
			require $this->plugin_path( 'framework/loader.php' );

			$this->framework = new Jet_Blocks_CX_Loader(
				array(
					$this->plugin_path( 'framework/interface-builder/cherry-x-interface-builder.php' ),
					$this->plugin_path( 'framework/breadcrumbs/cherry-x-breadcrumbs.php' ),
				)
			);
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
			if ( ! $this->has_elementor() ) {
				add_action( 'admin_notices', array( $this, 'required_plugins_notice' ) );
				return;
			}

			$this->load_files();

			jet_blocks_assets()->init();
			jet_blocks_integration()->init();
			jet_blocks_handlers()->init();
			jet_blocks_ext_elements()->init();

			if ( is_admin() ) {

				require $this->plugin_path( 'includes/updater/class-jet-blocks-plugin-update.php' );

				jet_blocks_updater()->init( array(
					'version' => $this->get_version(),
					'slug'    => 'jet-blocks',
				) );
			}
		}

		/**
		 * Show recommended plugins notice.
		 *
		 * @return void
		 */
		public function required_plugins_notice() {
			$screen = get_current_screen();

			if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
				return;
			}

			$plugin = 'elementor/elementor.php';

			$installed_plugins      = get_plugins();
			$is_elementor_installed = isset( $installed_plugins[ $plugin ] );

			if ( $is_elementor_installed ) {
				if ( ! current_user_can( 'activate_plugins' ) ) {
					return;
				}

				$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

				$message = sprintf( '<p>%s</p>', esc_html__( 'JetBlocks requires Elementor to be activated.', 'jet-blocks' ) );
				$message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $activation_url, esc_html__( 'Activate Elementor Now', 'jet-blocks' ) );
			} else {
				if ( ! current_user_can( 'install_plugins' ) ) {
					return;
				}

				$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

				$message = sprintf( '<p>%s</p>', esc_html__( 'JetBlocks requires Elementor to be installed.', 'jet-blocks' ) );
				$message .= sprintf( '<p><a href="%s" class="button-primary">%s</a></p>', $install_url, esc_html__( 'Install Elementor Now', 'jet-blocks' ) );
			}

			printf( '<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post( $message ) );
		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return did_action( 'elementor/loaded' );
		}

		/**
		 * Elementor instance
		 *
		 * @return object
		 */
		public function elementor() {
			return \Elementor\Plugin::$instance;
		}

		/**
		 * Load required files.
		 *
		 * @return void
		 */
		public function load_files() {
			require $this->plugin_path( 'includes/class-jet-blocks-assets.php' );
			require $this->plugin_path( 'includes/class-jet-blocks-tools.php' );
			require $this->plugin_path( 'includes/class-jet-blocks-integration.php' );
			require $this->plugin_path( 'includes/class-jet-blocks-settings.php' );
			require $this->plugin_path( 'includes/class-jet-blocks-handlers.php' );
			require $this->plugin_path( 'includes/class-jet-blocks-ext-elements.php' );
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
			load_plugin_textdomain( 'jet-blocks', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'jet-blocks/template-path', 'jet-blocks/' );
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
					'<a href="http://documentation.zemez.io/wordpress/index.php?project=jetblocks&lang=en&section=jetblocks-changelog" target="_blank">%s</a>',
					esc_html__( 'Changelog', 'jet-blocks' )
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

if ( ! function_exists( 'jet_blocks' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function jet_blocks() {
		return Jet_Blocks::get_instance();
	}
}

jet_blocks();
