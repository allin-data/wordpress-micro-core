<?php

/**
 * Class WPML_Jet_Elements_Price_List
 */
class WPML_Jet_Elements_Price_List extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'price_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_title', 'item_price', 'item_text', 'item_url' => array( 'url' ) );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'Jet Price List: Item Title', 'jet-elements' );

			case 'item_price':
				return esc_html__( 'Jet Price List: Item Price', 'jet-elements' );

			case 'item_text':
				return esc_html__( 'Jet Price List: Item Description', 'jet-elements' );

			case 'url':
				return esc_html__( 'Jet Price List: Item URL', 'jet-elements' );

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
			case 'item_price':
			case 'item_text':
				return 'LINE';

			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
