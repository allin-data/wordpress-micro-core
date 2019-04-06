<?php
/**
 * Cart list template
 */
?>
<div class="jet-blocks-cart__list">
	<?php $this->__html( 'cart_list_label', '<h4 class="jet-blocks-cart__list-title">%s</h4>' ); ?>
	<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
</div>
