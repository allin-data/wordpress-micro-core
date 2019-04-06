<?php
/**
 * Login Link template
 */
if ( ! $settings['show_logout_link'] ) {
	return;
}

if ( ! is_user_logged_in() && ! jet_blocks_integration()->in_elementor() ) {
	return;
}

$prefix       = $this->__get_html( 'logout_prefix', '<div class="jet-auth-links__prefix">%s</div>' );
$current_user = wp_get_current_user();

?>
<div class="jet-auth-links__section jet-auth-links__logout">
	<?php printf( $prefix, $current_user->display_name ); ?>
	<a class="jet-auth-links__item" href="<?php echo $this->__logout_url(); ?>"><?php
		$this->__html( 'logout_link_icon', '<i class="jet-auth-links__item-icon %s"></i>' );
		$this->__html( 'logout_link_text', '<span class="jet-auth-links__item-text">%s</span>' );
	?></a>
</div>