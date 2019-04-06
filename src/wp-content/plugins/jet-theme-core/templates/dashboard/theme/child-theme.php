<?php
/**
 * Child theme template
 */
?>
<div class="jet-child-theme">
	<h4 class="jet-child-theme__name"><?php _e( 'Child Theme', 'jet-theme-core' ); ?></h4>
	<div class="jet-child-theme__status">
		<b><?php _e( 'Status:', 'jet-theme-core' ); ?></b>
		<span><?php echo $child_status['message']; ?></span>
	</div>
	<?php if ( 'not_installed' === $child_status['code'] ) : ?>
	<div class="jet-child-theme__install">
		<button class="cx-button cx-button-primary-style" data-action="install-child" type="button"><?php
			_e( 'Install', 'jet-theme-core' );
		?></button>
	</div>
	<?php endif; ?>
	<div class="jet-child-theme__errors"></div>
</div>