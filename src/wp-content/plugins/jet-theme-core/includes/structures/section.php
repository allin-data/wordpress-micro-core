<?php

if ( ! class_exists( 'Jet_Theme_Core_Structure_Section' ) ) {

	/**
	 * Define Jet_Theme_Core_Structure_Section class
	 */
	class Jet_Theme_Core_Structure_Section extends Jet_Theme_Core_Structure_Base {

		public function get_id() {
			return 'jet_section';
		}

		public function get_single_label() {
			return esc_html__( 'Section', 'jet-theme-core' );
		}

		public function get_plural_label() {
			return esc_html__( 'Sections', 'jet-theme-core' );
		}

		public function get_sources() {
			return array( 'jet-theme', 'jet-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Jet_Section_Document',
				'file'  => jet_theme_core()->plugin_path( 'includes/document-types/section.php' ),
			);
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
