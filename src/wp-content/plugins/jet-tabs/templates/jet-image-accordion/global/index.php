<?php
/**
 * Images Layout template
 */
$settings = $this->get_settings();

$this->add_render_attribute( 'main-container', 'class', array(
	'jet-image-accordion',
	'jet-image-accordion-' . $settings['instance_orientation'] . '-orientation',
	'jet-image-accordion-' . $settings['anim_ease'] . '-ease',
) );

$this->add_render_attribute( 'main-container', 'data-settings', $this->generate_setting_json() );

?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
	<?php $this->__get_global_looped_template( 'image-accordion', 'item_list' ); ?>
</div>
