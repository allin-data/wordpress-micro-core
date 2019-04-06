<?php
/**
 * Playlist item title template
 */

$thumb = isset( $video_data['thumbnail_medium'] ) ? $video_data['thumbnail_medium'] : $video_data['thumbnail_default'];
$title = ! empty( $item['title'] ) ? $item['title'] : $video_data['title'];

?>
<div class="jet-blog-playlist__item-thumb<?php echo $hide['image']; ?>">
	<img src="<?php echo $thumb; ?>" alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" class="jet-blog-playlist__item-thumb-img">
</div>