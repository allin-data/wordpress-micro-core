<?php
/**
 * Activate license control
 */
?>
<div class="jet-core-license">
	<div class="jet-core-license__errors"><?php echo $error_message; ?></div>
	<label for="jet_core_license"><?php esc_html_e( 'Your License Key', 'jet-theme-core' ); ?></label>
	<div class="jet-core-license__form">
		<input id="jet_core_license" class="jet-core-license__input cx-ui-text" value="<?php echo $formated_license; ?>">
		<button type="button" class="cx-button cx-button-normal-style" id="jet_deactivate_license"><?php
			esc_html_e( 'Deactivate', 'jet-theme-core' );
		?></button>
	</div>
	<?php esc_html_e( 'Status:', 'jet-theme-core' ); ?>&nbsp;
	<?php
		$status = jet_theme_core()->api->license_status( $license );
		printf(
			'<span class="jet-core-license__status status-%1$s">%2$s</span>',
			( true === $status['success'] ? 'success' : 'error' ),
			$status['message']
		);
	?>
</div>