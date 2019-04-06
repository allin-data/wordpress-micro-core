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

if ( ! class_exists( 'Jet_Theme_Core_Ajax_Handlers' ) ) {

	/**
	 * Define Jet_Theme_Core_Ajax_Handlers class
	 */
	class Jet_Theme_Core_Ajax_Handlers {

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
				'jet_theme_search_posts' => array( $this, 'search_posts' ),
				'jet_theme_search_pages' => array( $this, 'search_pages' ),
				'jet_theme_search_cats'  => array( $this, 'search_cats' ),
				'jet_theme_search_tags'  => array( $this, 'search_tags' ),
				'jet_theme_search_terms' => array( $this, 'search_terms' ),
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
				'results' => Jet_Theme_Core_Utils::search_posts_by_type( 'page', $query ),
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
				'results' => Jet_Theme_Core_Utils::search_posts_by_type( $post_type, $query ),
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
				'results' => Jet_Theme_Core_Utils::search_terms_by_tax( 'category', $query ),
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
				'results' => Jet_Theme_Core_Utils::search_terms_by_tax( 'post_tag', $query ),
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
				'results' => Jet_Theme_Core_Utils::search_terms_by_tax( $tax, $query ),
			) );

		}

	}

}
