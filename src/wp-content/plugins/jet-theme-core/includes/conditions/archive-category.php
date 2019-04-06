<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Conditions_Archive_Category' ) ) {

	/**
	 * Define Jet_Theme_Core_Conditions_Archive_Category class
	 */
	class Jet_Theme_Core_Conditions_Archive_Category extends Jet_Theme_Core_Conditions_Base {

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
			return __( 'Category Archives', 'jet-theme-core' );
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
				'cats' => array(
					'label'       => __( 'Select Categories', 'jet-theme-core' ),
					'type'        => 'jet_search',
					'action'      => 'jet_theme_search_cats',
					'label_block' => true,
					'multiple'    => true,
					'description' => __( 'Leave empty to apply for all categories', 'jet-theme-core' ),
					'saved'       => $this->get_saved_cats(),
				),
			);
		}

		public function get_saved_cats() {

			$template_id = get_the_ID();
			$saved       = get_post_meta( $template_id, '_elementor_page_settings', true );

			if ( ! empty( $saved['conditions_archive-category_cats'] ) ) {

				$terms = get_terms( array(
					'include'    => $saved['conditions_archive-category_cats'],
					'taxonomy'   => 'category',
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

			if ( empty( $args['cats'] ) ) {
				return __( 'All', 'jet-theme-core' );
			}

			$result = '';
			$sep    = '';

			$terms = get_terms( array(
				'include'    => $args['cats'],
				'taxonomy'   => 'category',
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

			if ( empty( $args['cats'] ) ) {
				return is_category();
			}

			return is_category( $args['cats'] );
		}

	}

}