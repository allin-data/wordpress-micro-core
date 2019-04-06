<?php
/**
 * Posts navigation template
 */

$settings  = $this->get_settings();
$prev_text = isset( $settings['prev_text'] ) ? $settings['prev_text'] : '';
$next_text = isset( $settings['next_text'] ) ? $settings['next_text'] : '';

if ( ! empty( $settings['prev_icon'] ) ) {
	$prev_text = jet_blog_tools()->get_carousel_arrow( $settings['prev_icon'], 'prev' ) . $prev_text;
}

if ( ! empty( $settings['next_icon'] ) ) {
	$next_text .= jet_blog_tools()->get_carousel_arrow( $settings['next_icon'], 'next' );
}

the_posts_navigation( array(
	'prev_text' => $prev_text,
	'next_text' => $next_text,
) );
