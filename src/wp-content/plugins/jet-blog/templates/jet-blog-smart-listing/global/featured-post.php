<?php
/**
 * Feeatured post template
 */
global $post;

foreach ( $this->__get_query() as $post ) {

	setup_postdata( $post );

	$settings          = $this->__get_widget_settings();
	$layout            = $settings['featured_layout'];
	$context           = 'featured';
	$featured_meta_pos = isset( $settings['featured_meta_position'] ) ? $settings['featured_meta_position'] : 'after';

	?>
	<div class="<?php $this->__featured_post_classes(); ?>"<?php $this->__get_item_thumbnail_bg(); ?>>
	<?php

		$is_featured = true;

		$this->__post_terms( $is_featured );

		if ( 'simple' === $layout ) {
			$this->__featured_image( $context );
		} else {
			printf( '<a href="%s" class="jet-smart-listing__featured-box-link">', get_permalink() );
		}

		echo '<div class="jet-smart-listing__featured-content">';

			if ( 'before' === $featured_meta_pos ) {
				include $this->__get_global_template( 'post-meta' );
			}

			$this->__post_title( $context );

			if ( 'after' === $featured_meta_pos ) {
				include $this->__get_global_template( 'post-meta' );
			}

			$this->__post_excerpt( $context );

			if ( 'after-excerpt' === $featured_meta_pos ) {
				include $this->__get_global_template( 'post-meta' );
			}

			$this->__read_more( $context );
		echo '</div>';

		if ( 'simple' !== $layout ) {
			echo '</a>';
		}
	?>
	</div>
	<?php
}
