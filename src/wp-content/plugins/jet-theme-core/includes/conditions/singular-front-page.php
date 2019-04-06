<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Conditions_Front' ) ) {

	/**
	 * Define Jet_Theme_Core_Conditions_Front class
	 */
	class Jet_Theme_Core_Conditions_Front extends Jet_Theme_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-front-page';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Front Page', 'jet-theme-core' );
		}

		/**
		 * Condition group
		 *
		 * @return string
		 */
		public function get_group() {
			return 'singular';
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $args ) {
			return is_front_page();
		}

	}

}