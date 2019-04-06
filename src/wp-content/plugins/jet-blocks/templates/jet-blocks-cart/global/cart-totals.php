<?php
/**
 * Cart totals template
 */

$elementor    = Elementor\Plugin::instance();
$is_edit_mode = $elementor->editor->is_edit_mode();

if ( $is_edit_mode && ! wp_doing_ajax() ) {
	$totals = '';
} else {
	$totals = wp_kses_data( WC()->cart->get_cart_subtotal() );
}

?>
<span class="jet-blocks-cart__total-val"><?php
	echo $totals;
?></span>
