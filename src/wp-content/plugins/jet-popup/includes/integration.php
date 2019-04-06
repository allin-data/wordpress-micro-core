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

if ( ! class_exists( 'Jet_Popup_Integration' ) ) {

	/**
	 * Define Jet_Popup_Integration class
	 */
	class Jet_Popup_Integration {

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
		public function __construct() {
			add_action( 'elementor/init', array( $this, 'register_category' ) );

			add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_addons' ), 10 );

			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );
		}

		/**
		 * Register cherry category for elementor if not exists
		 *
		 * @return void
		 */
		public function register_category() {

			$elements_manager = Elementor\Plugin::instance()->elements_manager;
			$category         = 'jet-popup';

			$elements_manager->add_category(
				$category,
				array(
					'title' => esc_html__( 'Jet Popup', 'jet-popup' ),
					'icon'  => 'font',
				),
				1
			);
		}

		/**
		 * Register plugin addons
		 *
		 * @param  object $widgets_manager Elementor widgets manager instance.
		 * @return void
		 */
		public function register_addons( $widgets_manager ) {

			require jet_popup()->plugin_path( 'includes/base/class-jet-popup-base.php' );

			foreach ( glob( jet_popup()->plugin_path( 'includes/addons/' ) . '*.php' ) as $file ) {
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
		 * Add new controls.
		 *
		 * @param  object $controls_manager Controls manager instance.
		 * @return void
		 */
		public function add_controls( $controls_manager ) {

			$grouped = array(
				'jet-popup-box-style'       => 'Jet_Popup_Group_Control_Box_Style',
				'jet-popup-transform-style' => 'Jet_Popup_Group_Control_Transform_Style',
			);

			foreach ( $grouped as $control_id => $class_name ) {
				if ( $this->include_control( $class_name, true ) ) {
					$controls_manager->add_group_control( $control_id, new $class_name() );
				}
			}

			$controls = array(
				'jet_popup_search' => 'Jet_Popup_Control_Search',
			);

			foreach ( $controls as $control_id => $class_name ) {
				if ( $this->include_control( $class_name, false ) ) {
					$class_name = 'Elementor\\' . $class_name;
					$controls_manager->register_control( $control_id, new $class_name() );
				}
			}
		}

		/**
		 * Include control file by class name.
		 *
		 * @param  [type] $class_name [description]
		 * @return [type]             [description]
		 */
		public function include_control( $class_name, $grouped = false ) {

			$filename = sprintf(
				'includes/controls/%2$sclass-%1$s.php',
				str_replace( '_', '-', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( jet_popup()->plugin_path( $filename ) ) ) {
				return false;
			}

			require jet_popup()->plugin_path( $filename );

			return true;
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
