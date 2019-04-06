<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Conditions_Archive_Category' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Archive_Category class
	 */
	class Jet_Popup_Conditions_Archive_Category extends Jet_Popup_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'archive-category';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Category Archives', 'jet-popup' );
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
		 * [ajax_action description]
		 * @return [type] [description]
		 */
		public function ajax_action() {
			return 'jet_popup_search_cats';
		}

		/**
		 * [get_label_by_value description]
		 * @param  string $value [description]
		 * @return [type]        [description]
		 */
		public function get_label_by_value( $value = '' ) {

			$terms = get_terms( array(
				'include'    => $value,
				'taxonomy'   => 'category',
				'hide_empty' => false,
			) );

			$label = '';

			if ( ! empty( $terms ) ) {
				foreach ( $terms as $key => $term ) {
					$label .= $term->name;
				}
			}

			return $label;
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $arg = '' ) {

			if ( empty( $arg ) ) {
				return is_category();
			}

			return is_category( $arg );
		}

	}

}
