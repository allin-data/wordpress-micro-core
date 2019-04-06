<?php
/**
 * Timeline list template
 */

$settings = $this->get_settings_for_display();

$classes_list[] = 'jet-timeline';
$classes_list[] = 'jet-timeline--align-' . $settings['horizontal_alignment'];
$classes_list[] = 'jet-timeline--align-' . $settings['vertical_alignment'];
$classes = implode( ' ', $classes_list );

?>
<div class="<?php echo $classes ?>">
	<div class="jet-timeline__line"><div class="jet-timeline__line-progress"></div></div>
	<?php $this->__get_global_looped_template( 'timeline', 'cards_list' ); ?>
</div>