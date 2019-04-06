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

if ( ! class_exists( 'Jet_Elementor_Finder' ) ) {

	/**
	 * Define Jet_Elementor_Finder class
	 */
	class Jet_Elementor_Finder {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function __construct() {
			add_action( 'elementor/finder/categories/init', [ $this, 'add_jet_popup_category' ] );
		}

		/**
		 * [add_jet_popup_category description]
		 * @param [type] $categories_manager [description]
		 */
		public function add_jet_popup_category( $categories_manager ) {

			require jet_popup()->plugin_path( 'includes/elementor-finder/categories/popup-finder-category.php' );

			$categories_manager->add_category( 'jet-popup-finder-category', new Jet_Popup_Finder_Category() );
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
