<?php

/**
 * Class WPML_Jet_Elements_Slider
 */
class WPML_Jet_Elements_Slider extends WPML_Elementor_Module_With_Items {

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
		return array(
			'item_title',
			'item_subtitle',
			'item_desc',
			'item_button_primary_text',
			'item_button_secondary_text',
			'item_button_primary_url',
			'item_button_secondary_url',
		);
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'item_title':
				return esc_html__( 'Jet Slider: Slide Title', 'jet-elements' );

			case 'item_subtitle':
				return esc_html__( 'Jet Slider: Slide Subtitle', 'jet-elements' );

			case 'item_desc':
				return esc_html__( 'Jet Slider: Slide Description', 'jet-elements' );

			case 'item_button_primary_text':
				return esc_html__( 'Jet Slider: Slide Button Primary Text', 'jet-elements' );

			case 'item_button_secondary_text':
				return esc_html__( 'Jet Slider: Slide Button Secondary Text', 'jet-elements' );

			case 'item_button_primary_url':
				return esc_html__( 'Jet Slider: Slide Button Primary URL', 'jet-elements' );

			case 'item_button_secondary_url':
				return esc_html__( 'Jet Slider: Slide Button Secondary URL', 'jet-elements' );

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

			case 'item_subtitle':
				return 'LINE';

			case 'item_desc':
				return 'AREA';

			case 'item_button_primary_text':
				return 'LINE';

			case 'item_button_secondary_text':
				return 'LINE';

			case 'item_button_primary_url':
				return 'LINK';

			case 'item_button_secondary_url':
				return 'LINK';

			default:
				return '';
		}
	}

}
