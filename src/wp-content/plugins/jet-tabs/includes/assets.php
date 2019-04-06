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

if ( ! class_exists( 'Jet_Tabs_Assets' ) ) {

	/**
	 * Define Jet_Tabs_Assets class
	 */
	class Jet_Tabs_Assets {

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
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );
			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ) );

			add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {

			wp_enqueue_style(
				'jet-tabs-frontend',
				jet_tabs()->plugin_url( 'assets/css/jet-tabs-frontend.css' ),
				false,
				jet_tabs()->get_version()
			);
		}

		/**
		 * Enqueue plugin scripts only with elementor scripts
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script(
				'jet-tabs-frontend',
				jet_tabs()->plugin_url( 'assets/js/jet-tabs-frontend' . $suffix . '.js' ),
				array( 'jquery', 'elementor-frontend' ),
				jet_tabs()->get_version(),
				true
			);

			wp_localize_script( 'jet-tabs-frontend', 'JetTabsSettings', array(
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
			) );

		}

		/**
		 * Enqueue elemnetor editor-related styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-tabs-icon-font',
				jet_tabs()->plugin_url( 'assets/css/lib/jettabs-font/css/jettabs-icons.css' ),
				array(),
				jet_tabs()->get_version()
			);

			wp_enqueue_style(
				'jet-tabs-editor',
				jet_tabs()->plugin_url( 'assets/css/jet-tabs-editor.css' ),
				array(),
				jet_tabs()->get_version()
			);

		}

		/**
		 * Enqueue elemnetor editor scripts
		 *
		 * @return void
		 */
		public function editor_scripts() {

			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_script(
				'jet-tabs-editor',
				jet_tabs()->plugin_url( 'assets/js/jet-tabs-editor' . $suffix . '.js' ),
				array( 'jquery', 'underscore', 'backbone-marionette' ),
				jet_tabs()->get_version(),
				true
			);
		}

		/**
		 * Prints editor templates
		 *
		 * @return void
		 */
		public function print_templates() {

			/*foreach ( glob( jet_theme_core()->plugin_path( 'templates/editor/*.php' ) ) as $file ) {
				$name = basename( $file, '.php' );
				ob_start();
				include $file;
				printf( '<script type="text/html" id="tmpl-jet-%1$s">%2$s</script>', $name, ob_get_clean() );
			}*/

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
 * Returns instance of Jet_Tabs_Registration
 *
 * @return object
 */
function jet_tabs_assets() {
	return Jet_Tabs_Assets::get_instance();
}
