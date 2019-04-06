<?php
/**
 * WooCommerce customizer options
 *
 * @package Monstroid2
 */

if ( ! function_exists( 'monstroid2_set_wc_dynamic_css_options' ) ) {

	/**
	 * Add dynamic WooCommerce styles
	 *
	 * @param $options
	 *
	 * @return mixed
	 */
	function monstroid2_set_wc_dynamic_css_options( $options ) {

		array_push( $options['css_files'], get_theme_file_path( 'inc/modules/woo/assets/css/dynamic/woo-module-dynamic.css' ) );

		return $options;

	}

}
add_filter( 'monstroid2-theme/dynamic_css/options', 'monstroid2_set_wc_dynamic_css_options' );