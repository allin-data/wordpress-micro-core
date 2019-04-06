<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Conditions_Singular_Post' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Singular_Post class
	 */
	class Jet_Popup_Conditions_Singular_Post extends Jet_Popup_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-post';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Post', 'jet-popup' );
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
		 * [ajax_action description]
		 * @return [type] [description]
		 */
		public function ajax_action() {
			return 'jet_popup_search_posts';
		}

		/**
		 * [get_label_by_value description]
		 * @param  string $value [description]
		 * @return [type]        [description]
		 */
		public function get_label_by_value( $value = '' ) {

			return get_the_title( $value );
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $arg ) {

			if ( empty( $arg ) ) {
				return is_single();
			}

			return is_single( $arg );
		}

	}

}
