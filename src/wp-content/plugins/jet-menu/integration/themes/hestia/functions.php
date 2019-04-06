<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'jet_menu_hestia_styles', 0 );

/**
 * Enqueue hestia compatibility styles
 *
 * @return void
 */
function jet_menu_hestia_styles() {
	wp_enqueue_style(
		'jet-menu-hestia',
		jet_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		jet_menu()->get_version()
	);
}
