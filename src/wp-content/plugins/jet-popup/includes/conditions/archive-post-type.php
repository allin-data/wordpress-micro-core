<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Conditions_Archive_Post_Type' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Archive_Post_Type class
	 */
	class Jet_Popup_Conditions_Archive_Post_Type extends Jet_Popup_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'archive-post-type';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Post Type Archives', 'jet-popup' );
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
			return 'jet_popup_search_archive_types';
		}

		/**
		 * [get_label_by_value description]
		 * @param  string $value [description]
		 * @return [type]        [description]
		 */
		public function get_label_by_value( $value = '' ) {

			$obj = get_post_type_object( $value );

			return $obj->labels->singular_name;
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $arg = '' ) {

			if ( empty( $arg ) ) {
				return is_post_type_archive();
			}

			if ( 'post' === $arg && 'post' === get_post_type() ) {
				return is_archive() || is_home();
			}

			return is_post_type_archive( $arg ) || ( is_tax() && $arg === get_post_type() );
		}

	}

}
