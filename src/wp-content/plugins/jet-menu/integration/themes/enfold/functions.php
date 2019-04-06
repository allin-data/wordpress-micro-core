<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'jet_menu_enfold_scripts', 0 );
add_action( 'wp_enqueue_scripts', 'jet_menu_enfold_styles', 0 );

/**
 * Enqueue enfold compatibility script
 *
 * @return void
 */
function jet_menu_enfold_scripts() {
	wp_enqueue_script(
		'jet-menu-enfold',
		jet_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		jet_menu()->get_version(),
		true
	);
}

/**
 * Enqueue enfold compatibility styles
 *
 * @return void
 */
function jet_menu_enfold_styles() {
	wp_enqueue_style(
		'jet-menu-enfold',
		jet_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		jet_menu()->get_version()
	);
}
