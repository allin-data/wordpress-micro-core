<?php
/**
 * Register Link template
 */
if ( ! $settings['show_registered_link'] ) {
	return;
}

if ( ! is_user_logged_in() && ! jet_blocks_integration()->in_elementor() ) {
	return;
}

$url = $this->__get_url( $settings, 'registered_link_url' );

?>
<div class="jet-auth-links__section jet-auth-links__registered">
	<?php $this->__html( 'registered_prefix', '<div class="jet-auth-links__prefix">%s</div>' ); ?>
	<a class="jet-auth-links__item" href="<?php echo $url; ?>"><?php
		$this->__html( 'registered_link_icon', '<i class="jet-auth-links__item-icon %s"></i>' );
		$this->__html( 'registered_link_text', '<span class="jet-auth-links__item-text">%s</span>' );
	?></a>
</div>