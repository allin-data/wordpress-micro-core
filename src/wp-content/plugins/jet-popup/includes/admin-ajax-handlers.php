<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Popup_Admin_Ajax_Handlers' ) ) {

	/**
	 * Define Jet_Popup_Admin_Ajax_Handlers class
	 */
	class Jet_Popup_Admin_Ajax_Handlers {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			// Register private actions
			$priv_actions = array(
				'jet_popup_search_posts'          => array( $this, 'search_posts' ),
				'jet_popup_search_pages'          => array( $this, 'search_pages' ),
				'jet_popup_search_cats'           => array( $this, 'search_cats' ),
				'jet_popup_search_tags'           => array( $this, 'search_tags' ),
				'jet_popup_search_terms'          => array( $this, 'search_terms' ),
				'jet_popup_search_archive_types'  => array( $this, 'search_archive_types' ),
				'jet_popup_search_post_types'     => array( $this, 'search_post_types' ),
				'jet_popup_search_page_templates' => array( $this, 'search_page_templates' ),
				'jet_popup_save_conditions'       => array( $this, 'popup_save_conditions' ),
			);

			foreach ( $priv_actions as $tag => $callback ) {
				add_action( 'wp_ajax_' . $tag, $callback );
			}
		}

		/**
		 * Serch page
		 *
		 * @return [type] [description]
		 */
		public function search_pages() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';

			wp_send_json( array(
				'results' => Jet_Popup_Utils::search_posts_by_type( 'page', $query ),
			) );

		}

		/**
		 * Serch post
		 *
		 * @return [type] [description]
		 */
		public function search_posts() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query     = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';
			$post_type = isset( $_GET['preview_post_type'] ) ? esc_attr( $_GET['preview_post_type'] ) : 'post';

			wp_send_json( array(
				'results' => Jet_Popup_Utils::search_posts_by_type( $post_type, $query ),
			) );

		}

		/**
		 * Serch category
		 *
		 * @return [type] [description]
		 */
		public function search_cats() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';

			wp_send_json( array(
				'results' => Jet_Popup_Utils::search_terms_by_tax( 'category', $query ),
			) );

		}

		/**
		 * Serch tag
		 *
		 * @return [type] [description]
		 */
		public function search_tags() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';

			wp_send_json( array(
				'results' => Jet_Popup_Utils::search_terms_by_tax( 'post_tag', $query ),
			) );

		}

		/**
		 * Serach terms from passed taxonomies
		 * @return [type] [description]
		 */
		public function search_terms() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';
			$tax   = isset( $_GET['conditions_archive-tax_tax'] ) ? $_GET['conditions_archive-tax_tax'] : '';
			$tax   = explode( ',', $tax );

			wp_send_json( array(
				'results' => Jet_Popup_Utils::search_terms_by_tax( $tax, $query ),
			) );

		}

		/**
		 * Serach terms from passed taxonomies
		 * @return [type] [description]
		 */
		public function search_archive_types() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';

			$result = [];

			$types = Jet_Popup_Utils::get_post_types();

			if ( ! empty( $types ) ) {
				foreach ( $types as $type => $label ) {
					$result[] = array(
						'id'   => $type,
						'text' => $label,
					);
				}
			}

			wp_send_json( array(
				'results' => $result,
			) );
		}

		/**
		 * [search_post_types description]
		 * @return [type] [description]
		 */
		public function search_post_types() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';

			$result = [];

			$types = Jet_Popup_Utils::get_post_types();

			if ( ! empty( $types ) ) {
				foreach ( $types as $type => $label ) {
					$result[] = array(
						'id'   => $type,
						'text' => $label,
					);
				}
			}

			wp_send_json( array(
				'results' => $result,
			) );
		}

		/**
		 * [search_post_types description]
		 * @return [type] [description]
		 */
		public function search_page_templates() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? esc_attr( $_GET['q'] ) : '';

			$result = [];

			$templates = wp_get_theme()->get_page_templates();

			if ( ! empty( $templates ) ) {
				foreach ( $templates as $template => $label ) {
					$result[] = array(
						'id'   => $template,
						'text' => $label,
					);
				}
			}

			wp_send_json( array(
				'results' => $result,
			) );
		}

		/**
		 * [popup_save_conditions description]
		 * @return [type] [description]
		 */
		public function popup_save_conditions() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( [
					'type'    => 'error',
					'message' => [
						'title' => esc_html__( 'Error', 'jet-popup' ),
						'desc'  => esc_html__( 'You have no POWER!!!', 'jet-popup' )
					],
				] );
			}

			$popup_id = ( ! empty( $_POST['popup_id'] ) ) ? $_POST['popup_id'] : false;

			$conditions = ( ! empty( $_POST['conditions'] ) ) ? $_POST['conditions'] : [];

			if ( ! $popup_id ) {
				wp_send_json( [
					'type'    => 'error',
					'message' => [
						'title' => esc_html__( 'Error', 'jet-popup' ),
						'desc'  => esc_html__( 'Server Error', 'jet-popup' )
					],
				] );
			}

			jet_popup()->conditions->update_popup_conditions( $popup_id, $conditions );

			wp_send_json( [
				'type'    => 'success',
				'message' => [
					'title' => esc_html__( 'Success', 'jet-popup' ),
					'desc'  => esc_html__( 'Conditions have been saved!', 'jet-popup' ),
				],
			] );

		}
	}
}
