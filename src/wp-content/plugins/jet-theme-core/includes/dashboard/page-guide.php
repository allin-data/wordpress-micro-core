<?php
/**
 * User guide page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Dashboard_Guide' ) ) {

	/**
	 * Define Jet_Theme_Core_Dashboard_Guide class
	 */
	class Jet_Theme_Core_Dashboard_Guide extends Jet_Theme_Core_Dashboard_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'guide';
		}

		/**
		 * Get icon
		 *
		 * @return string
		 */
		public function get_icon() {
			return 'dashicons dashicons-info';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_attr__( 'User Guide', 'jet-theme-core' );
		}

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		public function render_page() {

			$guide = jet_theme_core()->config->get( 'guide' );

			$title   = ! empty( $guide['title'] ) ? $guide['title'] : '';
			$content = ! empty( $guide['content'] ) ? $guide['content'] : '';

			include jet_theme_core()->get_template( 'dashboard/guide/heading.php' );

			if ( ! empty( $guide['links'] ) ) {
				$links = $guide['links'];
				include jet_theme_core()->get_template( 'dashboard/guide/links.php' );
			}

		}

	}

}
