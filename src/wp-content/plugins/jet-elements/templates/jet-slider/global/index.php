<?php
/**
 * Slider template
 */
$settings = $this->get_settings_for_display();
$data_settings = $this->generate_setting_json();

$classes_list[] = 'jet-slider';
$classes_list[] = 'jet-slider__image-' . $settings['slide_image_scale_mode'];
$classes = implode( ' ', $classes_list );
?>

<div class="<?php echo $classes; ?>" <?php echo $data_settings; ?>>
	<?php $this->__get_global_looped_template( 'slider', 'item_list' ); ?>
</div>
