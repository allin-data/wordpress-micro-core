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

if ( ! class_exists( 'Jet_Theme_Core_Elementor_Integration' ) ) {

	/**
	 * Define Jet_Theme_Core_Elementor_Integration class
	 */
	class Jet_Theme_Core_Elementor_Integration {

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
		public function __construct() {
			add_action( 'elementor/controls/controls_registered', array( $this, 'add_controls' ), 10 );
		}

		/**
		 * Register new controls
		 */
		public function add_controls( $controls_manager ) {

			$controls = array(
				'jet_search' => 'Jet_Control_Search',
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
				'includes/controls/%2$s%1$s.php',
				str_replace( 'jet_control_', '', strtolower( $class_name ) ),
				( true === $grouped ? 'groups/' : '' )
			);

			if ( ! file_exists( jet_theme_core()->plugin_path( $filename ) ) ) {
				return false;
			}

			require jet_theme_core()->plugin_path( $filename );

			return true;
		}

	}

}
