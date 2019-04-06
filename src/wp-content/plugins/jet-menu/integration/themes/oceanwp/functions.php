<?php

add_filter( 'wp_nav_menu_items', 'jet_menu_oceanwp_fix_header_search', 999, 2 );
add_filter( 'wp_nav_menu_args', 'jet_menu_oceanwp_fix_menu_args', 100000 );
add_action( 'wp_enqueue_scripts', 'jet_menu_oceanwp_styles', 999 );


/**
 * Make header search in OceanWP theme compatible with JetMenu
 * @return [type] [description]
 */
function jet_menu_oceanwp_fix_header_search( $items, $args ) {
	if ( ! isset( $args->menu_class ) || 'jet-menu' !== $args->menu_class ) {
		return $items;
	}

	$items = str_replace(
		array(
			'search-toggle-li',
			'site-search-toggle',
		),
		array(
			'search-toggle-li jet-menu-item jet-simple-menu-item jet-regular-item jet-responsive-menu-item',
			'site-search-toggle top-level-link',
		),
		$items
	);

	return $items;

}

/**
 * Fix nav menu arguments
 * @return array
 */
function jet_menu_oceanwp_fix_menu_args( $args ) {

	if ( ! isset( $args['menu_class'] ) || 'jet-menu' !== $args['menu_class'] ) {
		return $args;
	}

	$args['link_before'] = '';
	$args['link_after']  = '';

	return $args;
}

/**
 * Enqueue oceanwp compatibility styles
 *
 * @return void
 */
function jet_menu_oceanwp_styles() {
	wp_enqueue_style(
		'jet-menu-oceanwp',
		jet_menu()->get_theme_url( 'assets/css/style.css' ),
		array(),
		jet_menu()->get_version()
	);
}
