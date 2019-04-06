<?php
/**
 * Activate license control
 */
?>
<div class="jet-core-license">
	<p><?php
		esc_html_e( 'Enter your Order ID here, to activate Monstroid2, and get feature updates, premium support and unlimited access to the template library.', 'monstroid2' );
	?></p>

	<ol>
		<li><?php esc_html_e( 'Log in to your account to get your license key.', 'monstroid2' ); ?></li>
		<li><?php esc_html_e( 'Copy the license key from your account and paste it below.', 'monstroid2' ); ?></li>
	</ol>

	<label for="jet_core_license"><?php esc_html_e( 'Your License Key', 'monstroid2' ); ?></label>
	<div class="jet-core-license__form">
		<input id="jet_core_license" class="jet-core-license__input cx-ui-text" placeholder="<?php esc_html_e( 'Please enter your license key here', 'monstroid2' ); ?>">
		<button type="button" class="cx-button cx-button-primary-style" id="jet_activate_license"><?php
			esc_html_e( 'Activate', 'monstroid2' );
		?></button>
	</div>
	<div class="jet-core-license__errors"><?php echo $error_message; ?></div>

</div>