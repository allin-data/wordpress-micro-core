<?php
/**
 * Listing main template
 */
global $post;
$context  = 'simple';

$this->__maybe_adjust_query();

$query    = $this->__get_query();
$settings = $this->__get_widget_settings();
$meta_pos = isset( $settings['meta_position'] ) ? $settings['meta_position'] : 'after';

if ( empty( $query ) ) {
	wp_reset_postdata();
	return;
}

?>
<div class="jet-smart-listing__posts">
	<?php

		foreach ( $query as $post ) {
			setup_postdata( $post );
			$is_featured = false;
			?>
			<div class="<?php $this->__post_classes(); ?>">
				<?php $this->__post_terms( $is_featured ); ?>
				<?php $this->__featured_image( $context ); ?>
				<div class="jet-smart-listing__post-content"><?php

					if ( 'before' === $meta_pos ) {
						include $this->__get_global_template( 'post-meta' );
					}

					$this->__post_title( $context );

					if ( 'after' === $meta_pos ) {
						include $this->__get_global_template( 'post-meta' );
					}

					$this->__post_excerpt( $context );

					if ( 'after-excerpt' === $meta_pos ) {
						include $this->__get_global_template( 'post-meta' );
					}

					$this->__read_more( $context );
				?></div>
			</div>
			<?php
		}

		wp_reset_postdata();
	?>
</div>
