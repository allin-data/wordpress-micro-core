<?php
/**
 * Playlist item title template
 */
$title = ! empty( $item['title'] ) ? $item['title'] : $video_data['title'];
?>
<div class="jet-blog-playlist__item-title"><?php echo $title; ?></div>