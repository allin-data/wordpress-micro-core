<?php
/**
 * Settings page
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Theme_Core_Dashboard_Settings' ) ) {

	/**
	 * Define Jet_Theme_Core_Dashboard_Settings class
	 */
	class Jet_Theme_Core_Dashboard_Settings extends Jet_Theme_Core_Dashboard_Base {

		/**
		 * Page slug
		 *
		 * @return string
		 */
		public function get_slug() {
			return 'settings';
		}

		/**
		 * Get icon
		 *
		 * @return string
		 */
		public function get_icon() {
			return 'dashicons dashicons-admin-settings';
		}

		/**
		 * Page name
		 *
		 * @return string
		 */
		public function get_name() {
			return esc_attr__( 'Settings', 'jet-theme-core' );
		}

		/**
		 * Disable builder instance initialization
		 *
		 * @return bool
		 */
		public function use_builder() {
			return false;
		}

		/**
		 * Renderer callback
		 *
		 * @return void
		 */
		public function render_page() {
			jet_theme_core()->settings->render_page();
		}

		public function save_settings( $data ) {
			jet_theme_core()->settings->save( $data );
		}

	}

}
