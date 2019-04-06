<?php
/**
 * Jet Menu polylang compatiibility
 */

add_filter( 'jet-menu/public-manager/menu-location', 'jet_menu_polylang_fix_location' );

/**
 * Fix menu location for Polylang plugin
 *
 * @param  array $settings Default settings
 * @return array
 */
function jet_menu_polylang_fix_location( $location ) {

	// Ensure Polylang is active.
	if ( ! function_exists( 'PLL' ) || ! PLL() instanceof PLL_Frontend ) {
		return $location;
	}

	$new_location = PLL()->combine_location( $location, PLL()->curlang );

	return $new_location;

}