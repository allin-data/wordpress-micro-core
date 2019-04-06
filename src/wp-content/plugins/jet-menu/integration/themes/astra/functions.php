<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'jet_menu_astra_scripts', 0 );
add_action( 'wp_enqueue_scripts', 'jet_menu_astra_styles', 0 );

/**
 * Enqueue astra compatibility script
 *
 * @return void
 */
function jet_menu_astra_scripts() {
	wp_enqueue_script(
		'jet-menu-astra',
		jet_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		jet_menu()->get_version(),
		true
	);
}

/**
 * Enqueue astra compatibility styles
 *
 * @return void
 */
function jet_menu_astra_styles() {
	wp_enqueue_style(
		'jet-menu-astra',
		jet_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		jet_menu()->get_version()
	);
}
