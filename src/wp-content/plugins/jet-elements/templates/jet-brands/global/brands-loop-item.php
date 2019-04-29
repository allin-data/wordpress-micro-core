<?php
/**
 * Features list item template
 */
?>
<div class="brands-list__item <?php echo jet_elements_tools()->col_classes( array(
	'desk' => $this->__get_html( 'columns' ),
	'tab'  => $this->__get_html( 'columns_tablet' ),
	'mob'  => $this->__get_html( 'columns_mobile' ),
) ); ?>"><?php
	echo $this->__open_brand_link( 'item_url' );
	echo $this->__get_brand_image( 'item_image' );
	echo $this->__loop_item( array( 'item_name' ), '<h5 class="brands-list__item-name">%s</h5>' );
	echo $this->__loop_item( array( 'item_desc' ), '<div class="brands-list__item-desc">%s</div>' );
	echo $this->__close_brand_link( 'item_url' );
?></div>