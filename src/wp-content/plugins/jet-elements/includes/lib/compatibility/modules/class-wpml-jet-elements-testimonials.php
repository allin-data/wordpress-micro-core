<?php

/**
 * Class WPML_Jet_Elements_Testimonials
 */
class WPML_Jet_Elements_Testimonials extends WPML_Elementor_Module_With_Items {

	/**
	 * @return string
	 */
	public function get_items_field() {
		return 'item_list';
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return array( 'item_title', 'item_comment', 'item_name', 'item_position', 'item_date' );
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'Jet Testimonials: Item Title', 'jet-elements' );

			case 'item_comment':
				return esc_html__( 'Jet Testimonials: Item Comment', 'jet-elements' );

			case 'item_name':
				return esc_html__( 'Jet Testimonials: Item Name', 'jet-elements' );

			case 'item_position':
				return esc_html__( 'Jet Testimonials: Item Position', 'jet-elements' );

			case 'item_date':
				return esc_html__( 'Jet Testimonials: Item Date', 'jet-elements' );

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

			case 'item_comment':
				return 'AREA';

			case 'item_name':
				return 'LINE';

			case 'item_position':
				return 'LINE';

			case 'item_date':
				return 'LINE';

			default:
				return '';
		}
	}

}
