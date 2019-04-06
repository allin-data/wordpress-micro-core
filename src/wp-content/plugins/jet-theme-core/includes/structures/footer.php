<?php

if ( ! class_exists( 'Jet_Theme_Core_Structure_Footer' ) ) {

	/**
	 * Define Jet_Theme_Core_Structure_Footer class
	 */
	class Jet_Theme_Core_Structure_Footer extends Jet_Theme_Core_Structure_Base {

		public function get_id() {
			return 'jet_footer';
		}

		public function get_single_label() {
			return esc_html__( 'Footer', 'jet-theme-core' );
		}

		public function get_plural_label() {
			return esc_html__( 'Footers', 'jet-theme-core' );
		}

		public function get_sources() {
			return array( 'jet-theme', 'jet-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Jet_Footer_Document',
				'file'  => jet_theme_core()->plugin_path( 'includes/document-types/footer.php' ),
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
			return 'footer';
		}

		/**
		 * Aproprite location name from Elementor Pro
		 * @return [type] [description]
		 */
		public function pro_location_mapping() {
			return 'footer';
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
