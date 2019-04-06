<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blog_Assets' ) ) {

	/**
	 * Define Jet_Blog_Assets class
	 */
	class Jet_Blog_Assets {

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
			add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_lib_assets' ), 0 );
		}

		/**
		 * Register 3rd party assets
		 *
		 * @return void
		 */
		public function register_lib_assets() {

			wp_register_script( 'youtube-iframe-api', 'https://www.youtube.com/iframe_api' );
			wp_register_script( 'vimeo-iframe-api', 'https://player.vimeo.com/api/player.js' );

		}

		/**
		 * Enqueue public-facing stylesheets.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function enqueue_styles() {

			wp_enqueue_style(
				'jet-blog',
				jet_blog()->plugin_url( 'assets/css/jet-blog.css' ),
				false,
				jet_blog()->get_version()
			);

			if ( is_rtl() ) {
				wp_enqueue_style(
					'jet-blog-rtl',
					jet_blog()->plugin_url( 'assets/css/jet-blog-rtl.css' ),
					false,
					jet_blog()->get_version()
				);
			}

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
 * Returns instance of Jet_Blog_Assets
 *
 * @return object
 */
function jet_blog_assets() {
	return Jet_Blog_Assets::get_instance();
}
