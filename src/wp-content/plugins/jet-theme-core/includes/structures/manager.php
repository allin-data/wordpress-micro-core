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

if ( ! class_exists( 'Jet_Theme_Core_Structures' ) ) {

	/**
	 * Define Jet_Theme_Core_Structures class
	 */
	class Jet_Theme_Core_Structures {

		private $_structures = null;

		public function __construct() {

			$this->register_structures();

			add_action( 'elementor/documents/register', array( $this, 'register_document_types_for_structures' ) );

			add_action( 'elementor/dynamic_tags/before_render', array( $this, 'switch_to_preview_query' ) );
			add_action( 'elementor/dynamic_tags/after_render', array( $this, 'restore_current_query' ) );

		}

		/**
		 * Switch to specific preview query
		 *
		 * @return void
		 */
		public function switch_to_preview_query() {

			$current_post_id = get_the_ID();
			$document        = Elementor\Plugin::instance()->documents->get_doc_or_auto_save( $current_post_id );

			if ( ! is_object( $document ) || ! method_exists( $document, 'get_preview_as_query_args' ) ) {
				return;
			}

			$new_query_vars = $document->get_preview_as_query_args();

			if ( empty( $new_query_vars ) ) {
				return;
			}

			Elementor\Plugin::instance()->db->switch_to_query( $new_query_vars );

		}

		/**
		 * Restore default query
		 *
		 * @return void
		 */
		public function restore_current_query() {
			Elementor\Plugin::instance()->db->restore_current_query();
		}

		/**
		 * Register apropriate Document Types for existing structures
		 *
		 * @return void
		 */
		public function register_document_types_for_structures( $documents_manager ) {

			require jet_theme_core()->plugin_path( 'includes/document-types/base.php' );

			foreach ( $this->_structures as $id => $structure ) {
				$document_type = $structure->get_document_type();
				require $document_type['file'];
				$documents_manager->register_document_type( $id, $document_type['class'] );
			}
		}

		/**
		 * Register default data structures
		 *
		 * @return void
		 */
		public function register_structures() {

			$base_path = jet_theme_core()->plugin_path( 'includes/structures/' );

			require $base_path . 'base.php';

			$structures = array(
				'Jet_Theme_Core_Structure_Page'    => $base_path . 'page.php',
				'Jet_Theme_Core_Structure_Header'  => $base_path . 'header.php',
				'Jet_Theme_Core_Structure_Footer'  => $base_path . 'footer.php',
				'Jet_Theme_Core_Structure_Section' => $base_path . 'section.php',
				'Jet_Theme_Core_Structure_Archive' => $base_path . 'archive.php',
				'Jet_Theme_Core_Structure_Single'  => $base_path . 'single.php',
			);

			foreach ( $structures as $class => $file ) {
				require $file;
				$this->register_structure( $class );
			}

			do_action( 'jet-theme-core/structures/register', $this );

		}

		public function register_structure( $class ) {
			$instance = new $class;
			$this->_structures[ $instance->get_id() ] = $instance;

			if ( true === $instance->is_location() ) {
				jet_theme_core()->locations->register_location( $instance->location_name(), $instance );
			}

		}

		/**
		 * Returns all structures data
		 *
		 * @return array
		 */
		public function get_structures() {
			return $this->_structures;
		}

		/**
		 * Returns all structures data
		 *
		 * @return object
		 */
		public function get_structure( $id ) {
			return isset( $this->_structures[ $id ] ) ? $this->_structures[ $id ] : false;
		}

		/**
		 * Return structures prepared for post type page tabs
		 * @return [type] [description]
		 */
		public function get_structures_for_post_type() {

			$result = array();

			foreach ( $this->_structures as $id => $structure ) {
				$result[ $id ] = $structure->get_single_label();
			}

			return $result;

		}

		/**
		 * Return structures prepared for popup tabs
		 *
		 * @return [type] [description]
		 */
		public function get_structures_for_popup() {

			$result = array();

			foreach ( $this->_structures as $id => $structure ) {
				$result[ $id ] = array(
					'title'    => $structure->get_plural_label(),
					'data'     => array(),
					'sources'  => $structure->get_sources(),
					'settings' => $structure->library_settings(),
				);
			}

			return $result;

		}

		/**
		 * Get post structure name for current post ID.
		 *
		 * @param  int $post_id Post ID
		 * @return string
		 */
		public function get_post_structure( $post_id ) {

			$doc_type = get_post_meta( $post_id, '_elementor_template_type', true );

			if ( ! $doc_type ) {
				return false;
			}

			$doc_structure = $this->get_structure( $doc_type );

			if ( ! $doc_structure ) {
				return false;
			} else {
				return $doc_structure;
			}

		}

	}

}
