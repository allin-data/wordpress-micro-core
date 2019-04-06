<?php

/**
 * Class WPML_Jet_Tricks_Hotspots
 */
class WPML_Jet_Tricks_Hotspots extends WPML_Elementor_Module_With_Items {

	/**
	 * Get Items field
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'hotspots';
	}

	/**
	 * Get fields
	 *
	 * @return array
	 */
	public function get_fields() {
		return array( 'hotspot_text', 'hotspot_description', 'hotspot_url' => array( 'url' ) );
	}

	/**
	 * Get title
	 *
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch( $field ) {
			case 'hotspot_text':
				return esc_html__( 'Jet Hotspots: Text', 'jet-tricks' );

			case 'hotspot_description':
				return esc_html__( 'Jet Hotspots: Description', 'jet-tricks' );

			case 'url':
				return esc_html__( 'Jet Hotspots: Link', 'jet-tricks' );

			default:
				return '';
		}
	}

	/**
	 * Get editor type
	 *
	 * @param string $field
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch( $field ) {
			case 'hotspot_text':
				return 'LINE';

			case 'hotspot_description':
				return 'AREA';

			case 'url':
				return 'LINK';

			default:
				return '';
		}
	}

}
