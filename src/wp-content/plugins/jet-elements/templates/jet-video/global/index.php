<?php
/**
 * Video main template
 */
$settings  = $this->get_settings_for_display();
$video_url = $this->get_video_url();

if ( empty( $video_url ) ) {
	return;
}

$video_html = $this->get_video_html();

if ( empty( $video_html ) ) {
	echo $video_url;

	return;
}

$data_settings = array(
	'autoplay' => filter_var( $settings['autoplay'], FILTER_VALIDATE_BOOLEAN ),
);

$this->add_render_attribute( 'wrapper', 'class', 'jet-video' );
$this->add_render_attribute( 'wrapper', 'data-settings', esc_attr( json_encode( $data_settings ) ) );

if ( ! empty( $settings['aspect_ratio'] ) ) {
	$this->add_render_attribute( 'wrapper', 'class', 'jet-video-aspect-ratio' );
	$this->add_render_attribute( 'wrapper', 'class', 'jet-video-aspect-ratio--' . esc_attr( $settings['aspect_ratio'] ) );
}
?>

<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>><?php
	echo $video_html;

	include $this->__get_global_template( 'overlay' );
?></div>
