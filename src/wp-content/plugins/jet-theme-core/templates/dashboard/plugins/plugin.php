<?php
/**
 * Jet Plugin item template
 */

?>
<div class="jet-plugin">
	<img src="<?php echo $thumb; ?>" alt="" class="jet-plugin__thumb">
	<div class="jet-plugin__content">
		<h4 class="jet-plugin__title"><?php echo $name; ?></h4>
		<div class="jet-plugin__version current-version">Current Version: <b><?php
			echo $this->get_latest_version( $plugin );
		?></b></div>
		<div class="jet-plugin__version user-version">Your Version: <b><?php if ( isset( $plugin_data['Version'] ) ) {
			echo $plugin_data['Version'];
		} else {
			echo '---';
		} ?></b></div>
		<div class="jet-plugin__actions">
			<?php if ( empty( $plugin_data ) ) : ?>
			<?php $this->install_plugin_link( $slug ); ?>
			<?php elseif ( $this->is_update_available( $plugin, $plugin_data ) ) : ?>
			<a href="#" data-action="update" data-plugin="<?php echo $slug; ?>" class="jet-plugin__actions-link"><?php
				_e( 'Update', 'jet-theme-core' );
			?></a>
			<?php endif; ?>
			<a href="<?php echo $docs; ?>" target="_blank" class="jet-plugin__actions-link"><?php
				_e( 'Documentation', 'jet-theme-core' );
			?></a>
		</div>
		<div class="jet-plugin__errors">
		</div>
	</div>
</div>