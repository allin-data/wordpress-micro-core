<?php
/**
 * Images Layout template
 */
$settings = $this->get_settings_for_display();
$data_settings = $this->generate_setting_json();

$classes_list[] = 'layout-type-' . $settings['layout_type'];
$classes = implode( ' ', $classes_list );
?>

<div class="jet-images-layout <?php echo $classes; ?>" <?php echo $data_settings; ?>>
	<?php $this->__get_global_looped_template( 'images-layout', 'image_list' ); ?>
</div>
