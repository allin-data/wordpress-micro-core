<?php
/**
 * Main video playlist template
 */

$settings     = $this->get_settings();
$list         = $settings['videos_list'];
$active_class = ' jet-blog-active';
$caching      = true;

if ( ! empty( $settings['disable_caching'] ) && 'yes' === $settings['disable_caching'] ) {
	$caching = false;
}

$hide = $this->__get_hide_classes( $settings );

?>
<div class="<?php $this->__container_classes( $settings ); ?>">
	<div class="jet-blog-playlist__canvas"><div class="jet-blog-playlist__canvas-overlay"></div></div>
	<div class="jet-blog-playlist__items">
		<?php include $this->__get_global_template( 'item-heading' ); ?>
		<div class="jet-blog-playlist__items-list">
			<div class="jet-blog-playlist__items-list-content"><?php
			foreach ( $list as $index => $item ) {

				$video_data = jet_blog_video_data()->get( $item['url'], $caching );

				printf(
					'<div class="jet-blog-playlist__item%2$s" %1$s>',
					$this->__get_video_data_atts( $video_data, $item, $settings, $index ),
					$active_class
				);
				include $this->__get_global_template( 'item-index' );
				include $this->__get_global_template( 'item-thumb' );
				echo '<div class="jet-blog-playlist__item-content">';
					include $this->__get_global_template( 'item-title' );
					include $this->__get_global_template( 'item-duration' );
				echo '</div>';
				echo '</div>';

				$active_class = '';
			}
			?></div>
		</div>
	</div>
</div>