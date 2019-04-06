<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Conditions_Archive_Post_Type' ) ) {

	/**
	 * Define Jet_Theme_Core_Conditions_Archive_Post_Type class
	 */
	class Jet_Theme_Core_Conditions_Archive_Post_Type extends Jet_Theme_Core_Conditions_Base {

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
			return __( 'Post Type Archives', 'jet-theme-core' );
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
			return array(
				'types' => array(
					'label'    => esc_html__( 'Post Type', 'jet-theme-core' ),
					'type'     => Elementor\Controls_Manager::SELECT2,
					'default'  => 'post',
					'options'  => Jet_Theme_Core_Utils::get_post_types(),
					'multiple' => true,
				),
			);
		}

		public function verbose_args( $args ) {

			if ( empty( $args['types'] ) ) {
				return __( 'All', 'jet-theme-core' );
			}

			$result = '';
			$sep    = '';

			foreach ( $args['types'] as $post_type ) {
				$obj     = get_post_type_object( $post_type );
				$label   = ! empty( $obj ) ? $obj->labels->singular_name : $post_type;
				$result .= $sep . $label;
				$sep     = ', ';
			}

			return $result;
		}

		/**
		 * Condition check callback
		 *
		 * @return bool
		 */
		public function check( $args ) {

			if ( empty( $args['types'] ) ) {
				return is_post_type_archive();
			}

			$types = $args['types'];

			if ( in_array( 'post', $types ) && 'post' === get_post_type() ) {
				return is_archive() || is_home();
			}

			return is_post_type_archive( $types ) || ( is_tax() && in_array( get_post_type(), $types ) );
		}

	}

}
