<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Conditions_Archive_Tag' ) ) {

	/**
	 * Define Jet_Theme_Core_Conditions_Archive_Tag class
	 */
	class Jet_Theme_Core_Conditions_Archive_Tag extends Jet_Theme_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'archive-tag';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Tag Archives', 'jet-theme-core' );
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
				'tags' => array(
					'label'       => __( 'Select Tags', 'jet-theme-core' ),
					'type'        => 'jet_search',
					'action'      => 'jet_theme_search_tags',
					'label_block' => true,
					'multiple'    => true,
					'description' => __( 'Leave empty to apply for all tags', 'jet-theme-core' ),
					'saved'       => $this->get_saved_tags(),
				),
			);
		}

		public function get_saved_tags() {

			$template_id = get_the_ID();
			$saved       = get_post_meta( $template_id, '_elementor_page_settings', true );

			if ( ! empty( $saved['conditions_archive-tag_tags'] ) ) {

				$terms = get_terms( array(
					'include'    => $saved['conditions_archive-tag_tags'],
					'taxonomy'   => 'post_tag',
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

			if ( empty( $args['tags'] ) ) {
				return __( 'All', 'jet-theme-core' );
			}

			$result = '';
			$sep    = '';

			$terms = get_terms( array(
				'include'    => $args['tags'],
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

			if ( empty( $args['tags'] ) ) {
				return is_tag();
			}

			return is_tag( $args['tags'] );
		}

	}

}