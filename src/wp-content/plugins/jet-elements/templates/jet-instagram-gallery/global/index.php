<?php
$settings = $this->get_settings_for_display();
$data_settings = $this->generate_setting_json();

$attr_array = array();
$class_array = array( 'jet-instagram-gallery__instance' );
$class_array[] = 'layout-type-' . $settings['layout_type'];

if ( 'grid' === $settings['layout_type'] ) {
	$class_array[] = 'col-row';
	$class_array[] = 'disable-cols-gap';
	$class_array[] = 'disable-rows-gap';
}

if ( filter_var( $settings['show_on_hover'], FILTER_VALIDATE_BOOLEAN ) ) {
	$class_array[] = 'show-overlay-on-hover';
}

if ( 'masonry' === $settings['layout_type'] ) {
	$attr_array[] = 'data-columns';
}

$columns        = $settings['columns'];
$columns_tablet = $settings['columns_tablet'];
$columns_mobile = $settings['columns_mobile'];

$columns        = empty( $columns ) ? 3 : $columns;
$columns_tablet = empty( $columns_tablet ) ? 2 : $columns_tablet;
$columns_mobile = empty( $columns_mobile ) ? 1 : $columns_mobile;

$class_array[] =  'column-desktop-' . $columns;
$class_array[] =  'column-tablet-' . $columns_tablet;
$class_array[] =  'column-mobile-' . $columns_mobile;

$classes = implode( ' ', $class_array );
$attrs = implode( ' ', $attr_array );
?>

<div class="<?php echo $classes; ?>" <?php echo $attrs; ?> <?php echo $data_settings; ?>><?php
	$this->render_gallery();
?></div>
