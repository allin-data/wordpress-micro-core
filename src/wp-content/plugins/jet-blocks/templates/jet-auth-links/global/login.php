<?php
/**
 * Login Link template
 */
if ( ! $settings['show_login_link'] ) {
	return;
}

if ( is_user_logged_in() && ! jet_blocks_integration()->in_elementor() ) {
	return;
}

$url = $this->__get_url( $settings, 'login_link_url' );

?>
<div class="jet-auth-links__section jet-auth-links__login">
	<?php $this->__html( 'login_prefix', '<div class="jet-auth-links__prefix">%s</div>' ); ?>
	<a class="jet-auth-links__item" href="<?php echo $url; ?>"><?php
		$this->__html( 'login_link_icon', '<i class="jet-auth-links__item-icon %s"></i>' );
		$this->__html( 'login_link_text', '<span class="jet-auth-links__item-text">%s</span>' );
	?></a>
</div>