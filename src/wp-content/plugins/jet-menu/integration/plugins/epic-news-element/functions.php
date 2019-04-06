<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

add_action( 'admin_enqueue_scripts', 'jet_menu_epic_ne_admin_scripts', 99 );

function jet_menu_epic_ne_admin_scripts( $hook ) {
	if ( 'nav-menus.php' === $hook ) {
		wp_dequeue_script( 'bootstrap-iconpicker' );
	}
}
