<?php
/**
 * Menus configuration.
 *
 * @package Monstroid2
 */

add_action( 'after_setup_theme', 'monstroid2_register_menus', 5 );
function monstroid2_register_menus() {

	register_nav_menus( array(
		'main'   => esc_html__( 'Main', 'monstroid2' ),
		'footer' => esc_html__( 'Footer', 'monstroid2' ),
		'social' => esc_html__( 'Social', 'monstroid2' ),
	) );
}
