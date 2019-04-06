<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Conditions_Singular_Post_From_Tag' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Singular_Post_From_Tag class
	 */
	class Jet_Popup_Conditions_Singular_Post_From_Tag extends Jet_Popup_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-post-from-tag';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Posts from Tag', 'jet-popup' );
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
			return 'jet_popup_search_tags';
		}

		/**
		 * [get_label_by_value description]
		 * @param  string $value [description]
		 * @return [type]        [description]
		 */
		public function get_label_by_value( $value = '' ) {

			$terms = get_terms( array(
				'include'    => $value,
				'taxonomy'   => 'post_tag',
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

			return has_tag( $arg, $post );
		}

	}

}
