<?php
/**
 * Is front page condition
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Conditions_Singular_Page_Child' ) ) {

	/**
	 * Define Jet_Theme_Core_Conditions_Singular_Page_Child class
	 */
	class Jet_Theme_Core_Conditions_Singular_Page_Child extends Jet_Theme_Core_Conditions_Base {

		/**
		 * Condition slug
		 *
		 * @return string
		 */
		public function get_id() {
			return 'singular-page-child';
		}

		/**
		 * Condition label
		 *
		 * @return string
		 */
		public function get_label() {
			return __( 'Page, Child of', 'jet-theme-core' );
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
			return array(
				'pages' => array(
					'label'       => __( 'Select Parent', 'jet-theme-core' ),
					'type'        => 'jet_search',
					'action'      => 'jet_theme_search_pages',
					'label_block' => true,
					'multiple'    => false,
					'saved'       => $this->get_saved_pages(),
				),
			);
		}

		public function get_saved_pages() {

			$template_id = get_the_ID();
			$saved       = get_post_meta( $template_id, '_elementor_page_settings', true );

			if ( ! empty( $saved['conditions_singular-page-child_pages'] ) ) {

				if ( ! is_array( $saved['conditions_singular-page-child_pages'] ) ) {
					$pages = array( $saved['conditions_singular-page-child_pages'] );
				} else {
					$pages = $saved['conditions_singular-page-child_pages'];
				}

				$posts = get_posts( array(
					'post_type'           => 'page',
					'post__in'            => $pages,
					'ignore_sticky_posts' => true,
				) );

				if ( empty( $posts ) ) {
					return array();
				} else {
					return wp_list_pluck( $posts, 'post_title', 'ID' );
				}

			} else {
				return array();
			}
		}

		public function verbose_args( $args ) {

			if ( empty( $args['pages'] ) ) {
				return __( 'Not Selected', 'jet-theme-core' );
			}

			$result = '';
			$sep    = '';

			if ( ! is_array( $args['pages'] ) ) {
				$parents = array( $args['pages'] );
			} else {
				$parents = $args['pages'];
			}

			foreach ( $parents as $page ) {
				$result .= $sep . get_the_title( $page );
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

			if ( empty( $args['pages'] ) ) {
				return false;
			}

			if ( ! is_page() ) {
				return false;
			}

			if ( ! is_array( $args['pages'] ) ) {
				$parents = array( $args['pages'] );
			} else {
				$parents = $args['pages'];
			}

			global $post;

			return in_array( $post->post_parent, $parents );
		}

	}

}