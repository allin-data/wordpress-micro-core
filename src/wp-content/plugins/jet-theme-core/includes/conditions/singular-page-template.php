<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Conditions_Singular_Page_Template' ) ) {

	/**
	 * Define Jet_Theme_Core_Conditions_Singular_Page_Template class
	 */
	class Jet_Theme_Core_Conditions_Singular_Page_Template extends Jet_Theme_Core_Conditions_Base {

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
			return __( 'Page Template', 'jet-theme-core' );
		}

		/**
		 * Condition group
		 *
		 * @return string
		 */
		public function get_group() {
			return 'singular';
		}

		public function get_controls() {

			$templates = wp_get_theme()->get_page_templates();
			$templates = array( '' => __( 'Select...', 'jet-theme-core' ) ) + $templates;

			return array(
				'template' => array(
					'label'       => __( 'Select Template', 'jet-theme-core' ),
					'type'     => Elementor\Controls_Manager::SELECT,
					'default'  => '',
					'options'  => $templates,
				),
			);
		}

		public function verbose_args( $args ) {

			if ( empty( $args['template'] ) ) {
				return __( 'Not Selected', 'jet-theme-core' );
			}

			return $args['template'];
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $args ) {

			if ( empty( $args['template'] ) ) {
				return false;
			}

			if ( ! is_page() ) {
				return false;
			}

			global $post;

			$page_template_slug = get_page_template_slug( $post->ID );

			return $page_template_slug === $args['template'];
		}

	}

}