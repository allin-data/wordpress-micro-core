<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Conditions_Singular_Page_Template' ) ) {

	/**
	 * Define Jet_Popup_Conditions_Singular_Page_Template class
	 */
	class Jet_Popup_Conditions_Singular_Page_Template extends Jet_Popup_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-page-template';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Page Template', 'jet-popup' );
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
			return 'jet_popup_search_page_templates';
		}

		/**
		 * [get_label_by_value description]
		 * @param  string $value [description]
		 * @return [type]        [description]
		 */
		public function get_label_by_value( $value = '' ) {
			$template_label = '';

			$templates = wp_get_theme()->get_page_templates();

			if ( ! empty( $templates ) ) {
				foreach ( $templates as $template => $label ) {

					if ( $template === $value ) {
						$template_label = $template;
					}
				}
			}

			return $template_label;
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

			if ( ! is_page() ) {
				return false;
			}

			global $post;

			$page_template_slug = get_page_template_slug( $post->ID );

			return $page_template_slug === $arg;
		}

	}

}
