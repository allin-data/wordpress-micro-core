<?php
/**
 * Playlist item title template
 */
$show_index = ! empty( $settings['show_item_index'] ) ? $settings['show_item_index'] : false;

if ( 'yes' !== $show_index ) {
	return;
}

?>
<div class="jet-blog-playlist__item-index<?php echo $hide['index']; ?>">
	<div class="jet-blog-playlist__item-index-num"><?php echo ($index + 1); ?></div>
	<div class="jet-blog-playlist__item-status jet-status-playing"><i class="fa fa-play" aria-hidden="true"></i></div>
	<div class="jet-blog-playlist__item-status jet-status-paused"><i class="fa fa-pause" aria-hidden="true"></i></div>
</div>