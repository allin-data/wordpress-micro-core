<?php
/**
 * Progress Bar template
 */
$settings = $this->get_settings_for_display();

$this->add_render_attribute( 'main-container', 'class', array(
	'jet-progress-bar',
	'jet-progress-bar-' . $settings['progress_type'],
) );

$this->add_render_attribute( 'main-container', 'data-percent', $settings['percent'] );
$this->add_render_attribute( 'main-container', 'data-type', $settings['progress_type'] );

?>
<div <?php echo $this->get_render_attribute_string( 'main-container' ); ?>>
	<?php include $this->__get_type_template( $settings['progress_type'] ); ?>
</div>
