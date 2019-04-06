<?php
/**
 * Image accordion list start template
 */

$this->add_render_attribute( 'inner-container', 'class', array(
	'jet-image-accordion__list',
) );

?>
<div <?php echo $this->get_render_attribute_string( 'inner-container' ); ?>>
