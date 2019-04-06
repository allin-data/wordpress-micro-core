<?php
/**
 * Posts pagination template
 */

$settings  = $this->get_settings();
$prev_next = isset( $settings['prev_next'] ) ? $settings['prev_next'] : '';
$prev_next = filter_var( $prev_next, FILTER_VALIDATE_BOOLEAN );
$prev_text = isset( $settings['prev_text'] ) ? $settings['prev_text'] : '';
$next_text = isset( $settings['next_text'] ) ? $settings['next_text'] : '';

if ( ! empty( $settings['prev_icon'] ) ) {
	$prev_text = jet_blog_tools()->get_carousel_arrow( $settings['prev_icon'], 'prev' ) . $prev_text;
}

if ( ! empty( $settings['next_icon'] ) ) {
	$next_text .= jet_blog_tools()->get_carousel_arrow( $settings['next_icon'], 'next' );
}

the_posts_pagination( array(
	'prev_next' => $prev_next,
	'prev_text' => $prev_text,
	'next_text' => $next_text,
) );
