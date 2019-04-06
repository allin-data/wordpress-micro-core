<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Conditions_Singular_Post_From_Category' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Singular_Post_From_Category class
	 */
	class Jet_Popup_Conditions_Singular_Post_From_Category extends Jet_Popup_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-post-from-cat';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Posts from Category', 'jet-popup' );
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
				return false;
			}

			if ( ! is_single() ) {
				return false;
			}

			global $post;

			return in_category( $arg, $post );
		}

	}

}
