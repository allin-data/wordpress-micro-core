<?php

/**
 * Class WPML_Jet_Elements_Pricing_Table
 */
class WPML_Jet_Elements_Pricing_Table extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'features_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_text' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_text':
				return esc_html__( 'Jet Pricing Table: Features Item Text', 'jet-elements' );

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
			case 'item_text':
				return 'LINE';

			default:
				return '';
		}
	}

}
