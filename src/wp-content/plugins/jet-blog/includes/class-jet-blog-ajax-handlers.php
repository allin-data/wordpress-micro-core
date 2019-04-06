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

if ( ! class_exists( 'Jet_Blog_Ajax_Handlers' ) ) {

	/**
	 * Define Jet_Blog_Ajax_Handlers class
	 */
	class Jet_Blog_Ajax_Handlers {

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
		public function init() {

			add_action( 'wp_ajax_jet_blog_smart_listing_get_posts', array( $this, 'get_listing_posts' ) );
			add_action( 'wp_ajax_nopriv_jet_blog_smart_listing_get_posts', array( $this, 'get_listing_posts' ) );

		}

		/**
		 * Request Smart Listing posts callback
		 *
		 * @return void
		 */
		public function get_listing_posts() {

			if ( ! class_exists( 'Elementor\Jet_Blog_Base' ) ) {
				require jet_blog()->plugin_path( 'includes/base/class-jet-blog-base.php' );
			}

			if ( ! class_exists( 'Elementor\Jet_Blog_Smart_Listing' ) ) {
				require jet_blog()->plugin_path( 'includes/addons/jet-blog-smart-listing.php' );
			}

			$widget = new Elementor\Jet_Blog_Smart_Listing();

			$widget->__get_posts();
			$widget->__context = 'render';

			ob_start();
			$widget->__render_posts();
			$posts = ob_get_clean();

			ob_start();
			$widget->__get_arrows();
			$arrows = ob_get_clean();

			wp_send_json_success( array(
				'posts'  => $posts,
				'arrows' => $arrows,
			) );

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Blog_Ajax_Handlers
 *
 * @return object
 */
function jet_blog_ajax_handlers() {
	return Jet_Blog_Ajax_Handlers::get_instance();
}
