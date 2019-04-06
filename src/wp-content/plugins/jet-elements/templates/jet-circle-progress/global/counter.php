<?php
/**
 * Circle progress counter template
 */

$perc_position   = $this->get_settings_for_display( 'percent_position' );
$labels_position = $this->get_settings_for_display( 'labels_position' );

?>
<div class="circle-counter">
	<?php if ( $perc_position === $this->__processed_item ) { ?>
	<div class="circle-val"><?php
		$this->__html( 'prefix', '<span class="circle-counter__prefix">%s</span>' );
		include $this->__get_global_template( 'counter-number' );
		$this->__html( 'suffix', '<span class="circle-counter__suffix">%s</span>' );
	?></div>
	<?php } ?>
	<?php if ( $labels_position === $this->__processed_item ) { ?>
	<div class="circle-counter__content">
		<?php $this->__html( 'title', '<div class="circle-counter__title">%s</div>' ); ?>
		<?php $this->__html( 'subtitle', '<div class="circle-counter__subtitle">%s</div>' ); ?>
	</div>
	<?php } ?>
</div>