<?php
/**
 * WooCommerce archive product hooks.
 *
 * @package Monstroid2
 */

add_action( 'woocommerce_before_shop_loop', 'monstroid2_wc_loop_products_panel_open', 15 );
add_action( 'woocommerce_before_shop_loop', 'monstroid2_wc_loop_products_panel_close', 50 );
add_filter( 'woocommerce_product_loop_start', 'monstroid2_wc_product_loop_start' );

if ( ! function_exists( 'monstroid2_wc_loop_products_panel_open' ) ) {

	/**
	 * Archive products panel wrapper open
	 */
	function monstroid2_wc_loop_products_panel_open() {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}

		echo '<div class="woocommerce-products__panel">';
	}

}

if ( ! function_exists( 'monstroid2_wc_loop_products_panel_close' ) ) {

	/**
	 * Archive products panel wrapper close
	 */
	function monstroid2_wc_loop_products_panel_close() {
		if ( ! wc_get_loop_prop( 'is_paginated' ) || ! woocommerce_products_will_display() ) {
			return;
		}

		echo '</div>';
	}

}

if ( ! function_exists( 'monstroid2_wc_product_loop_start' ) ) {

	/**
	 * Rewrite loop start columns
	 *
	 * @param $ob_get_clean
	 *
	 * @return string
	 */
	function monstroid2_wc_product_loop_start( $ob_get_clean ) {

		$context = wc_get_loop_prop( 'name' );
		$columns = array(
			'xs' => 1,
			'sm' => 2,
			'md' => 2,
			'lg' => wc_get_loop_prop( 'columns' ),
			'xl' => wc_get_loop_prop( 'columns' ),
		);

		switch ( $context ) {
			case 'related':
				$columns['xl'] = 4;
				$columns['lg'] = 4;
				break;
			case 'up-sells':
				$columns['xl'] = 4;
				$columns['lg'] = 4;
				break;
			case 'cross-sells':
				$columns['xl'] = 4;
				$columns['lg'] = 4;
				break;
			default:
				break;
		}

		if( $columns['md'] > $columns['lg'] ){
			$columns['md'] = $columns['lg'];
		}

		if( $columns['sm'] > $columns['md'] ){
			$columns['sm'] = $columns['md'];
		}

		$columns = apply_filters( 'monstroid2-theme/woo/products_loop_columns', $columns, $context );

		if ( is_shop() || is_product_taxonomy() || is_product() ) {
			$ob_get_clean = sprintf(
				'<ul class="products products-grid columns-xs-%1$s columns-sm-%2$s columns-md-%3$s columns-lg-%4$s columns-xl-%5$s">',
				esc_attr( $columns['xs'] ),
				esc_attr( $columns['sm'] ),
				esc_attr( $columns['md'] ),
				esc_attr( $columns['lg'] ),
				esc_attr( $columns['xl'] )
			);
		}

		if ( apply_filters( 'monstroid2-theme/woo/products-loop-categories/show', true ) ){
			$ob_get_clean = woocommerce_maybe_show_product_subcategories( $ob_get_clean );
		}

		return $ob_get_clean;
	}

}
