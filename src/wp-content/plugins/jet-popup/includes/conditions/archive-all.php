<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Conditions_Archive_All' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Archive_All class
	 */
	class Jet_Popup_Conditions_Archive_All extends Jet_Popup_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'archive-all';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'All Archives', 'jet-popup' );
		}

		/**
		 * Condition group
		 *
		 * @return string
		 */
		public function get_group() {
			return 'archive';
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $args ) {
			return is_archive();
		}

	}

}
