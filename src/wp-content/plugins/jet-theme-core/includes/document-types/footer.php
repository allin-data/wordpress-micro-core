<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Jet_Footer_Document extends Jet_Document_Base {

	public function get_name() {
		return 'jet_footer';
	}

	public static function get_title() {
		return __( 'Footer', 'jet-theme-core' );
	}

}