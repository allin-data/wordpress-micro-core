<?php

/**
 * Class WPML_Jet_Elements_Images_Layout
 */
class WPML_Jet_Elements_Images_Layout extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'image_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_title', 'item_desc', 'item_url' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'Jet Images Layout: Item Title', 'jet-elements' );

			case 'item_desc':
				return esc_html__( 'Jet Images Layout: Item Description', 'jet-elements' );

			case 'item_url':
				return esc_html__( 'Jet Images Layout: External Link', 'jet-elements' );

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
			case 'item_title':
				return 'LINE';

			case 'item_desc':
				return 'AREA';

			case 'item_url':
				return 'LINK';

			default:
				return '';
		}
	}

}
