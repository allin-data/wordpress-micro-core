<?php
/**
 * Cart Link
 */
$this->add_render_attribute( 'cart-link', 'href', esc_url( wc_get_cart_url() ) );
$this->add_render_attribute( 'cart-link', 'class', 'jet-blocks-cart__heading-link' );
$this->add_render_attribute( 'cart-link', 'title', esc_attr__( 'View your shopping cart', 'jet-blocks' ) );

?>
<a <?php echo $this->get_render_attribute_string( 'cart-link' ); ?>><?php

	$this->__html( 'cart_icon', '<span class="jet-blocks-cart__icon"><i class="%s"></i></span>' );
	$this->__html( 'cart_label', '<span class="jet-blocks-cart__label">%s</span>' );

	if ( 'yes' === $settings['show_count'] ) {
		?>
		<span class="jet-blocks-cart__count"><?php
			ob_start();
			include $this->__get_global_template( 'cart-count' );
			printf( $settings['count_format'], ob_get_clean() );
		?></span>
		<?php
	}

	if ( 'yes' === $settings['show_total'] ) {
		?>
		<span class="jet-blocks-cart__total"><?php
			ob_start();
			include $this->__get_global_template( 'cart-totals' );
			printf( $settings['total_format'], ob_get_clean() );
		?></span>
		<?php
	}

?></a>