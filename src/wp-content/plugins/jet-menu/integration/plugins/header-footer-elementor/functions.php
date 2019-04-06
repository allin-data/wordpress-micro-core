<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'wp_enqueue_scripts', 'jet_menu_hfe_styles', 0 );

/**
 * Enqueue header-footer-elementor compatibility styles
 *
 * @return void
 */
function jet_menu_hfe_styles() {
	wp_enqueue_style(
		'jet-menu-hfe',
		jet_menu()->plugin_url( 'integration/plugins/header-footer-elementor/assets/css/style.css' ),
		array(),
		jet_menu()->get_version()
	);
}
