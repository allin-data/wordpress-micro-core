<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_filter( 'wp_nav_menu_args', 'jet_menu_betheme_fix_menu_args', 100000 );
add_action( 'wp_enqueue_scripts', 'jet_menu_betheme_scripts', 0 );

/**
 * Fix nav menu arguments
 * @return array
 */
function jet_menu_betheme_fix_menu_args( $args ) {

	if ( ! isset( $args['menu_class'] ) || 'jet-menu' !== $args['menu_class'] ) {
		return $args;
	}

	$args['link_before'] = '';
	$args['link_after']  = '';

	return $args;
}

/**
 * Enqueue enfold compatibility script
 *
 * @return void
 */
function jet_menu_betheme_scripts() {
	wp_enqueue_script(
		'jet-menu-betheme',
		jet_menu()->get_theme_url( 'assets/js/script.js' ),
		array( 'jquery' ),
		jet_menu()->get_version(),
		true
	);
}
