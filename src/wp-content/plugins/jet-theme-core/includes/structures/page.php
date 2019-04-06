<?php

if ( ! class_exists( 'Jet_Theme_Core_Structure_Page' ) ) {

	/**
	 * Define Jet_Theme_Core_Structure_Page class
	 */
	class Jet_Theme_Core_Structure_Page extends Jet_Theme_Core_Structure_Base {

		public function get_id() {
			return 'jet_page';
		}

		public function get_single_label() {
			return esc_html__( 'Page', 'jet-theme-core' );
		}

		public function get_plural_label() {
			return esc_html__( 'Pages', 'jet-theme-core' );
		}

		public function get_sources() {
			return array( 'jet-theme', 'jet-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Jet_Page_Document',
				'file'  => jet_theme_core()->plugin_path( 'includes/document-types/page.php' ),
			);
		}

	}

}
