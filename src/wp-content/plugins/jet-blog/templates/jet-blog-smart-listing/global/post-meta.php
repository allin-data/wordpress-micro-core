<?php
/**
 * Post meta template
 */

$settings = $this->__get_widget_settings();
$allowed  = ( true === $is_featured ) ? 'featured_show_meta' : 'show_meta';

if ( 'yes' !== $settings[ $allowed ] ) {
	return;
}

$meta_data = $this->__get_meta( $is_featured );

echo '<div class="jet-smart-listing__meta">';

	do_action( 'jet-blog/smart-listing/post-meta/before', $is_featured, $this );

	jet_blog_post_tools()->get_author( array(
		'visible' => $meta_data['author']['visible'],
		'class'   => 'posted-by__author',
		'prefix'  => $meta_data['author']['prefix'],
		'html'    => $meta_data['author']['html'],
		'echo'    => true,
	) );

	jet_blog_post_tools()->get_date( array(
		'visible' => $meta_data['date']['visible'],
		'class'   => 'post__date-link ',
		'icon'    => '',
		'prefix'  => $meta_data['date']['prefix'],
		'html'    => $meta_data['date']['html'],
		'echo'    => true,
	) );

	jet_blog_post_tools()->get_comment_count( array(
		'visible' => $meta_data['comments']['visible'],
		'class'   => 'post__comments-link',
		'icon'    => '',
		'prefix'  => $meta_data['comments']['prefix'],
		'html'    => $meta_data['comments']['html'],
		'echo'    => true,
	) );

	do_action( 'jet-blog/smart-listing/post-meta/after', $is_featured, $this );

echo '</div>';
