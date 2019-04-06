<?php
/**
 * Jet Core template
 */

?>
<div class="jet-plugin jet-core">
	<img src="<?php echo $logo; ?>" alt="" class="jet-plugin__thumb">
	<div class="jet-plugin__content">
		<h4 class="jet-plugin__title"><?php echo $plugin_data['Name']; ?></h4>
		<div class="jet-plugin__version current-version">Current Version: <b><?php
			echo $latest_version;
		?></b></div>
		<div class="jet-plugin__version user-version">Your Version: <b><?php if ( isset( $plugin_data['Version'] ) ) {
			echo $plugin_data['Version'];
		} else {
			echo '---';
		} ?></b></div>
		<div class="jet-plugin__actions">
			<?php if ( $has_update ) : ?>
			<a href="#" data-action="update" data-plugin="<?php echo $slug; ?>" class="jet-plugin__actions-link"><?php
				_e( 'Update', 'jet-theme-core' );
			?></a>
			<?php endif; ?>
			<a href="<?php echo $docs; ?>" target="_blank" class="jet-plugin__actions-link"><?php
				_e( 'Documentation', 'jet-theme-core' );
			?></a>
			<?php if ( $license_key ) : ?>
			<a href="<?php echo $this->sync_library_url(); ?>" class="jet-plugin__actions-link"><?php
				_e( 'Synchronize Templates Library', 'jet-theme-core' );
			?></a>
			<?php endif; ?>
		</div>
		<div class="jet-plugin__errors">
		</div>
	</div>
</div>