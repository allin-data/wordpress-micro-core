<?php
/**
 * Overlay template
 */

$thumb_url = $this->get_thumbnail_url();

if ( empty( $thumb_url ) && ! filter_var( $settings['show_play_button'], FILTER_VALIDATE_BOOLEAN ) ) {
	return;
}

$this->add_render_attribute( 'overlay', 'class', 'jet-video__overlay' );

if ( ! empty( $thumb_url ) ) {
	$this->add_render_attribute( 'overlay', 'class', 'jet-video__overlay--custom-bg' );
	$this->add_render_attribute( 'overlay', 'style', sprintf( 'background-image: url(%s);', $thumb_url ) );
}
?>

<div <?php $this->print_render_attribute_string( 'overlay' ); ?>><?php
	if ( filter_var( $settings['show_play_button'], FILTER_VALIDATE_BOOLEAN ) ) {
		include $this->__get_global_template( 'play-button' );
	}
?></div>
