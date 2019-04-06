<?php
/**
 * Timeline list item template
 */

$settings      = $this->get_settings_for_display();
$layout        = $settings['vertical_layout'];
$item_settings = $this->__processed_item;

$this->add_render_attribute(
	'item_bottom_' . $item_settings['_id'],
	array(
		'class' => array(
			'jet-hor-timeline-item',
			'elementor-repeater-item-' . esc_attr( $item_settings['_id'] )
		),
		'data-item-id' => esc_attr( $item_settings['_id'] )
	)
);

if ( filter_var( $item_settings['is_item_active'], FILTER_VALIDATE_BOOLEAN ) ) {
	$this->add_render_attribute( 'item_bottom_' . $item_settings['_id'], 'class', 'is-active' );
}
?>

<div <?php $this->print_render_attribute_string( 'item_bottom_' . $item_settings['_id'] ) ?>>
	<?php
	switch ( $layout ) {
		case 'top':

			include $this->__get_global_template( 'meta' );

			break;

		case 'chess':

			if ( $this->__processed_index % 2 ) {
				include $this->__get_global_template( 'card' );
			} else {
				include $this->__get_global_template( 'meta' );
			}

			break;

		case 'bottom':

			include $this->__get_global_template( 'card' );

			break;
	}
	?>
</div>
