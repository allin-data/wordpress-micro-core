<?php

/**
 * Class WPML_Jet_Elements_Brands
 */
class WPML_Jet_Elements_Brands extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'brands_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_name', 'item_desc', 'item_url' => array( 'url' ) );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_name':
				return esc_html__( 'Jet Brands: Company Name', 'jet-elements' );

			case 'item_desc':
				return esc_html__( 'Jet Brands: Company Description', 'jet-elements' );

			case 'url':
				return esc_html__( 'Jet Brands: Company URL', 'jet-elements' );

			default:
				return '';
		}
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'item_name':
				return 'LINE';

			case 'item_desc':
				return 'AREA';

			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
