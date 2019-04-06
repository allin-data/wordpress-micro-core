<?php
/**
 * Main Crocoblock theme template
 */
?>
<div class="jet-theme">
	<img src="<?php echo $remote_data['theme_thumb']; ?>" class="jet-theme__thumb">
	<div class="jet-theme__content">
		<h4 class="jet-theme__name"><?php echo $remote_data['theme_name']; ?></h4>
		<div class="jet-theme__status"><?php echo $theme_status['message']; ?></div>
		<div class="jet-theme__version"><?php

			echo '<span class="jet-theme__version-val">';
			echo $theme_status['version'];
			echo '</span>';

			if ( $has_update ) {

				$update_link = sprintf(
					'<a href="#" class="jet-theme__update-link" data-action="update-theme">%1$s</a>',
					__( 'Update now', 'jet-theme-core' )
				);

				printf(
					'<span class="jet-theme__update-notice">%1$s - %2$s - %3$s</span>',
					__( 'New version available', 'jet-theme-core' ),
					$remote_data['theme_version'],
					$update_link
				);
			} elseif ( $installed ) {
				printf(
					'<a href="%1$s" class="jet-theme__check-updates">%2$s</a>',
					$this->get_check_updates_url(),
					__( 'Check Updates', 'jet-theme-core' )
				);
			}
		?></div>
	</div>
	<div class="jet-theme__errors"></div>
</div>