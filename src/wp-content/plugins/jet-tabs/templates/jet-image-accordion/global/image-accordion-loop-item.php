<?php
/**
 * Image accordion list item template
 */
?>
<div class="jet-image-accordion__item">
<?php echo $this->__loop_item( array( 'item_image', 'url' ), '<img class="jet-image-accordion__image-instance" src="%s" alt="">' ); ?>
	<div class="jet-image-accordion__content"><?php
		echo $this->__loop_item( array( 'item_title' ), '<h5 class="jet-image-accordion__title">%s</h5>' );
		echo $this->__loop_item( array( 'item_desc' ), '<div class="jet-image-accordion__desc">%s</div>' );
		echo $this->__generate_action_button();?></div>
	<div class="jet-image-accordion__item-loader"><span></span></div>
</div>
