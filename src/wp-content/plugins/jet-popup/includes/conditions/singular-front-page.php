<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Conditions_Front' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Front class
	 */
	class Jet_Popup_Conditions_Front extends Jet_Popup_Conditions_Base {

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
			return __( 'Front Page', 'jet-popup' );
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
		public function check( $arg = '' ) {
			return is_front_page();
		}

	}

}
