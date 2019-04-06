<?php echo $this->blocks_separator(); ?>
<div class="jet-countdown-timer__item item-hours">
	<div class="jet-countdown-timer__item-value" data-value="hours"><?php echo $this->date_placeholder(); ?></div>
	<?php $this->__html( 'label_hours', '<div class="jet-countdown-timer__item-label">%s</div>' ); ?>
</div>
