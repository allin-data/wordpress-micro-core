<?php
/**
 * Playlist item title template
 */

$show_duration = ! empty( $settings['show_item_duration'] ) ? $settings['show_item_duration'] : false;

if ( ! $show_duration ) {
	return;
}

$duration = ! empty( $video_data['duration'] ) ? $video_data['duration'] : false;

if ( ! $duration ) {
	return;
}

?>
<div class="jet-blog-playlist__item-duration<?php echo $hide['duration']; ?>"><?php echo $duration; ?></div>