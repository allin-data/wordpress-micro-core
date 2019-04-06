<?php
/**
 * Main cart template
 */
?>
<div class="jet-blocks-cart">
	<div class="jet-blocks-cart__heading"><?php
		include $this->__get_global_template( 'cart-link' );
	?></div>

	<?php if ( 'yes' === $settings['show_cart_list'] ) {
		include $this->__get_global_template( 'cart-list' );
	} ?>
</div>