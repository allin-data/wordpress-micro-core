<?php

if ( ! class_exists( 'Jet_Theme_Core_Structure_Single' ) ) {

	/**
	 * Define Jet_Theme_Core_Structure_Single class
	 */
	class Jet_Theme_Core_Structure_Single extends Jet_Theme_Core_Structure_Base {

		public function get_id() {
			return 'jet_single';
		}

		public function get_single_label() {
			return esc_html__( 'Single', 'jet-theme-core' );
		}

		public function get_plural_label() {
			return esc_html__( 'Single', 'jet-theme-core' );
		}

		public function get_sources() {
			return array( 'jet-theme', 'jet-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Jet_Single_Document',
				'file'  => jet_theme_core()->plugin_path( 'includes/document-types/single.php' ),
			);
		}

		/**
		 * Is current structure could be outputed as location
		 *
		 * @return boolean
		 */
		public function is_location() {
			return true;
		}

		/**
		 * Location name
		 *
		 * @return boolean
		 */
		public function location_name() {
			return 'single';
		}

		/**
		 * Aproprite location name from Elementor Pro
		 * @return [type] [description]
		 */
		public function pro_location_mapping() {
			return 'single';
		}

		/**
		 * Library settings for current structure
		 *
		 * @return void
		 */
		public function library_settings() {

			return array(
				'show_title'    => false,
				'show_keywords' => true,
			);

		}

	}

}
