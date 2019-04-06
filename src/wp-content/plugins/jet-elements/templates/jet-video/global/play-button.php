<?php
/**
 * Play button template
 */

$this->add_render_attribute( 'play_button', 'class', 'jet-video__play-button' );
$this->add_render_attribute( 'play_button', 'role', 'button' );

if ( ! empty( $settings['play_button_hover_animation'] ) ) {
	$this->add_render_attribute( 'play_button', 'class', 'jet-video__play-button--animation-' . esc_attr( $settings['play_button_hover_animation'] ) );
}
?>

<div <?php $this->print_render_attribute_string( 'play_button' ); ?>><?php
	if ( 'icon' === $settings['play_button_type'] ) {
		printf( '<i class="jet-video__play-button-icon %s" aria-hidden="true"></i>', esc_attr( $settings['play_button_icon'] ) );
	} elseif ( 'image' === $settings['play_button_type'] ) {
		echo jet_elements_tools()->get_image_by_url(
			$settings['play_button_image']['url'],
			array(
				'class' => 'jet-video__play-button-image',
				'alt'   => esc_html__( 'Play Video', 'jet-elements' ),
			)
		);
	} ?>
	<span class="elementor-screen-only"><?php esc_html_e( 'Play Video', 'jet-elements' ); ?></span>
</div>
