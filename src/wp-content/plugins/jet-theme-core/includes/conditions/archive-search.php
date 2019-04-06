<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Conditions_Archive_Search' ) ) {

	/**
	 * Define Jet_Theme_Core_Conditions_Archive_Search class
	 */
	class Jet_Theme_Core_Conditions_Archive_Search extends Jet_Theme_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'archive-search';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Search Results', 'jet-theme-core' );
		}

		/**
		 * Condition group
		 *
		 * @return string
		 */
		public function get_group() {
			return 'archive';
		}

		public function get_controls() {
			return array();
		}

		public function verbose_args( $args ) {
			return '';
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $args ) {
			return is_search();
		}

	}

}