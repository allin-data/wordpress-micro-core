<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Menu_Integration' ) ) {

	/**
	 * Define Jet_Menu_Integration class
	 */
	class Jet_Menu_Integration {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Initalize integration hooks
		 *
		 * @return void
		 */
		public function init() {
			add_action( 'elementor/init', array( $this, 'register_category' ) );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_addons' ), 10 );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );
		}

		/**
		 * Enqueue editor styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'jet-menu-font',
				jet_menu()->plugin_url( 'assets/public/lib/jetmenu-font/css/jetmenu.css' ),
				array(),
				jet_menu()->get_version()
			);

		}

		/**
		 * Register plugin addons
		 *
		 * @param  object $widgets_manager Elementor widgets manager instance.
		 * @return void
		 */
		public function register_addons( $widgets_manager ) {

			foreach ( glob( jet_menu()->plugin_path( 'includes/widgets/' ) . '*.php' ) as $file ) {
				$this->register_addon( $file, $widgets_manager );
			}

		}

		/**
		 * Register addon by file name
		 *
		 * @param  string $file            File name.
		 * @param  object $widgets_manager Widgets manager instance.
		 * @return void
		 */
		public function register_addon( $file, $widgets_manager ) {

			$base  = basename( str_replace( '.php', '', $file ) );
			$class = ucwords( str_replace( '-', ' ', $base ) );
			$class = str_replace( ' ', '_', $class );
			$class = sprintf( 'Elementor\%s', $class );

			require $file;

			if ( class_exists( $class ) ) {
				$widgets_manager->register_widget_type( new $class );
			}
		}

		/**
		 * Register cherry category for elementor if not exists
		 *
		 * @return void
		 */
		public function register_category() {

			$elements_manager = Elementor\Plugin::instance()->elements_manager;
			$existing         = $elements_manager->get_categories();
			$cherry_cat       = 'cherry';

			if ( array_key_exists( $cherry_cat, $existing ) ) {
				return;
			}

			$elements_manager->add_category(
				$cherry_cat,
				array(
					'title' => esc_html__( 'Jet Elements', 'jet-menu' ),
					'icon'  => 'font',
				),
				1
			);
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance( $shortcodes = array() ) {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self( $shortcodes );
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Menu_Integration
 *
 * @return object
 */
function jet_menu_integration() {
	return Jet_Menu_Integration::get_instance();
}
