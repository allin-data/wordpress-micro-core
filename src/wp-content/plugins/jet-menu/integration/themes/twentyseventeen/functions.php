<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'jet_menu_twentyseventeen_scripts', 0 );

/**
 * Enqueue twentyseventeen compatibility script
 *
 * @return void
 */
function jet_menu_twentyseventeen_scripts() {
	wp_enqueue_script(
		'jet-menu-twentyseventeen',
		jet_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		jet_menu()->get_version(),
		true
	);
}
