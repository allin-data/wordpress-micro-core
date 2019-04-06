<?php
/**
 * Timeline list item template
 */
$settings      = $this->get_settings_for_display();
$item_settings = $this->__processed_item;

$classes = array(
	'jet-timeline-item',
	$settings['animate_cards'],
	'elementor-repeater-item-' . $item_settings['_id']
);

$item_meta_attr = $this->get_item_inline_editing_attributes( 'item_meta', 'cards_list', $this->__processed_item_index, 'timeline-item__meta-content' );
$item_title_attr = $this->get_item_inline_editing_attributes( 'item_title', 'cards_list', $this->__processed_item_index, 'timeline-item__card-title' );
$item_desc_attr = $this->get_item_inline_editing_attributes( 'item_desc', 'cards_list', $this->__processed_item_index, 'timeline-item__card-desc' );

$classes = implode( ' ', $classes );
$this->__processed_item_index += 1;
?>
<div class="<?php echo $classes ?>">
	<div class="timeline-item__card">
		<div class="timeline-item__card-inner">
				<?php
					if ( 'yes' === $item_settings['show_item_image'] ) {
						echo $this->__loop_item( array( 'item_image', 'url' ), '<div class="timeline-item__card-img"><img src="%s" alt=""></div>' );
					}
				?>
				<div class="timeline-item__card-content">
					<?php
						echo '<div class="timeline-item__meta">';
						echo $this->__loop_item( array( 'item_meta' ), '<div ' . $item_meta_attr . '>%s</div>' );
						echo '</div>';
						echo $this->__loop_item( array( 'item_title' ) , '<h5 ' . $item_title_attr . '>%1s</h5>' );
						echo $this->__loop_item( array( 'item_desc' ), '<div ' . $item_desc_attr . '>%s</div>' );
					?>
				</div>
		</div>
		<div class="timeline-item__card-arrow"></div>
	</div>
	<?php
		$this->_generate_point_content( $item_settings );
		echo '<div class="timeline-item__meta">';
		echo $this->__loop_item( array( 'item_meta' ), '<div ' . $item_meta_attr . '>%s</div>' );
		echo '</div>';
	?>
</div>