<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Conditions_Archive_Tax' ) ) {

	/**
	 * Define Jet_Theme_Core_Conditions_Archive_Tax class
	 */
	class Jet_Theme_Core_Conditions_Archive_Tax extends Jet_Theme_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'archive-tax';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Custom Taxonomy Archives', 'jet-theme-core' );
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
				'tax' => array(
					'label'    => esc_html__( 'Taxonomy', 'jet-theme-core' ),
					'type'     => Elementor\Controls_Manager::SELECT2,
					'default'  => '',
					'options'  => Jet_Theme_Core_Utils::get_taxonomies(),
					'multiple' => true,
				),
				'terms' => array(
					'label'        => __( 'Select Terms', 'jet-theme-core' ),
					'type'         => 'jet_search',
					'action'       => 'jet_theme_search_terms',
					'query_params' => array( 'conditions_archive-tax_tax' ),
					'label_block'  => true,
					'multiple'     => true,
					'description'  => __( 'Leave empty to apply for all terms', 'jet-theme-core' ),
					'saved'        => $this->get_saved_tags(),
				),
			);
		}

		public function get_saved_tags() {

			$template_id = get_the_ID();
			$saved       = get_post_meta( $template_id, '_elementor_page_settings', true );


			if ( empty( $saved['conditions_archive-tax_tax'] ) ) {
				return array();
			}

			$tax = $saved['conditions_archive-tax_tax'];

			if ( ! empty( $saved['conditions_archive-tax_terms'] ) ) {

				$terms = get_terms( array(
					'include'    => $saved['conditions_archive-tax_terms'],
					'taxonomy'   => $tax,
					'hide_empty' => false,
				) );

				if ( empty( $terms ) ) {
					return array();
				} else {
					return wp_list_pluck( $terms, 'name', 'term_id' );
				}

			} else {
				return array();
			}

		}

		public function verbose_args( $args ) {

			if ( empty( $args['tax'] ) ) {
				return __( 'All', 'jet-theme-core' );
			}

			$result = '';
			$sep    = '';

			$terms = get_terms( array(
				'include'    => $args['tax'],
				'taxonomy'   => 'post_tag',
				'hide_empty' => false,
			) );

			foreach ( $terms as $term ) {
				$result .= $sep . $term->name;
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

			if ( empty( $args['tax'] ) ) {
				return is_tax();
			}

			if ( ! empty( $args['tax'] ) && empty( $args['terms'] ) ) {
				return is_tax( $args['tax'] );
			}

			if ( ! empty( $args['terms'] ) ) {
				return is_tax( $args['tax'], $args['terms'] );
			}
		}

	}

}