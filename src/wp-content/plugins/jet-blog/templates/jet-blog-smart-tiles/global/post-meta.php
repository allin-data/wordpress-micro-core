<?php
/**
 * Post meta template
 */

$settings = $this->get_settings();

if ( 'yes' !== $settings[ 'show_meta' ] ) {
	return;
}

$meta_data = $this->__get_meta();

echo '<div class="jet-smart-tiles__meta">';

	do_action( 'jet-blog/smart-tiles/post-meta/before', $this );

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

	do_action( 'jet-blog/smart-tiles/post-meta/after', $this );

echo '</div>';