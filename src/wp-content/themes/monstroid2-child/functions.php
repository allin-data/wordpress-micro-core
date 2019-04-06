<?php
/**
 * Child functions and definitions.
 */
add_action( 'wp_enqueue_scripts', 'monstroid2_child_enqueue_styles' );

/**
 * Enqueue styles.
 */
function monstroid2_child_enqueue_styles() {
	wp_enqueue_style( 'monstroid2-parent-theme-style', get_template_directory_uri() . '/style.css' );
}
