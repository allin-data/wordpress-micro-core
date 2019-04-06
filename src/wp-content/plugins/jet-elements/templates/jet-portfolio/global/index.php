<?php
/**
 * Portfolio template
 */

$settings = $this->get_settings_for_display();

$preset        = $settings['preset'];
$layout        = $settings['layout_type'];
$columns       = $settings['columns'];
$columnsTablet = $settings['columns_tablet'];
$columnsMobile = $settings['columns_mobile'];

$this->add_render_attribute( 'main-container', 'class', array(
	'jet-portfolio',
	'layout-type-' . $layout,
	'preset-' . $preset,
) );

if ( 'masonry' == $layout || 'grid' == $layout ) {
	$this->add_render_attribute( 'main-container', 'class', array(
		'layout-desktop-column-' . $columns,
		! empty( $columnsTablet ) ? 'layout-tablet-column-' . $columnsTablet : false,
		! empty( $columnsMobile ) ? 'layout-mobile-column-' . $columnsMobile : false,
	) );
}

$this->add_render_attribute( 'main-container', 'data-settings', $this->generate_setting_json() );
?>

<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>><?php
	$this->render_filters();
	$this->__get_global_looped_template( esc_attr( $preset ) . '/portfolio', 'image_list' );
	$this->render_view_more_button();?>
</div>
