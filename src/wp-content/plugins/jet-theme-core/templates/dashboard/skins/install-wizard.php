<?php
/**
 * Install wizard message
 */

$all_plugins = get_plugins();
$wizard      = $this->wizard_data();
$slug        = sprintf( '%1$s/%1$s.php', $wizard['slug'] );

if ( array_key_exists( $slug, $all_plugins ) ) {
	$action = 'activate-wizard';
	$label  = esc_html__( 'Activate', 'jet-theme-core' );
} else {
	$action = 'install-wizard';
	$label  = esc_html__( 'Install Plugin', 'jet-theme-core' );
}

?>
<div class="jet-install-wizard">
	<div class="jet-install-wizard__content"><?php
		_e( 'You need to install and activate <b>JetPluginsWizard</b> to manage skins', 'jet-theme-core' );
	?></div>
	<a href="#" data-action="<?php echo $action; ?>" class="cx-button cx-button-primary-style"><?php
		echo $label;
	?></a>
	<div class="jet-install-wizard__msg"></div>
</div>