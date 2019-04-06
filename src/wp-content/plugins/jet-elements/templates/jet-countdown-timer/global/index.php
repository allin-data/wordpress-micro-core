<div class="jet-countdown-timer" data-due-date="<?php echo $this->due_date(); ?>">
	<?php $this->__glob_inc_if( '00-days', array( 'show_days' ) ); ?>
	<?php $this->__glob_inc_if( '01-hours', array( 'show_hours' ) ); ?>
	<?php $this->__glob_inc_if( '02-minutes', array( 'show_min' ) ); ?>
	<?php $this->__glob_inc_if( '03-seconds', array( 'show_sec' ) ); ?>
</div>
