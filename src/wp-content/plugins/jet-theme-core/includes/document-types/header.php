<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Header_Document extends Jet_Document_Base {

	public function get_name() {
		return 'jet_header';
	}

	public static function get_title() {
		return __( 'Header', 'jet-theme-core' );
	}

}