<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Section_Document extends Jet_Document_Base {

	public function get_name() {
		return 'jet_page';
	}

	public static function get_title() {
		return __( 'Section', 'jet-theme-core' );
	}

	public function has_conditions() {
		return false;
	}

}