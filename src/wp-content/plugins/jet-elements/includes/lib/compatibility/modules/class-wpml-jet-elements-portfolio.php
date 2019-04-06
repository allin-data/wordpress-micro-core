<?php

/**
 * Class WPML_Jet_Elements_Portfolio
 */
class WPML_Jet_Elements_Portfolio extends WPML_Elementor_Module_With_Items {

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
		return array( 'item_category', 'item_title', 'item_desc', 'item_button_text', 'item_button_url' => array( 'url' ) );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_category':
				return esc_html__( 'Jet Portfolio: Item Category', 'jet-elements' );

			case 'item_title':
				return esc_html__( 'Jet Portfolio: Item Title', 'jet-elements' );

			case 'item_desc':
				return esc_html__( 'Jet Portfolio: Item Description', 'jet-elements' );

			case 'item_button_text':
				return esc_html__( 'Jet Portfolio: Item Link Text', 'jet-elements' );

			case 'url':
				return esc_html__( 'Jet Portfolio: Item Link URL', 'jet-elements' );

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
			case 'item_category':
				return 'LINE';

			case 'item_title':
				return 'LINE';

			case 'item_desc':
				return 'AREA';

			case 'item_button_text':
				return 'LINE';

			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
