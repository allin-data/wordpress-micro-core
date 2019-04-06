<?php
/**
 * Template part for displaying default posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Monstroid2
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('posts-list__item default-item'); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="default-item__thumbnail" <?php monstroid2_post_overlay_thumbnail( 'monstroid2-thumb-m-2' ); ?>></div>
	<?php endif; ?>

	<div class="default-item__content">

		<?php
			monstroid2_post_comments( array(
				'prefix' => '<i class="fa fa-comment" aria-hidden="true"></i>'
			) );
		?>

		<header class="entry-header">
			<div class="entry-meta"><?php
				monstroid2_posted_by();
				monstroid2_posted_in( array(
					'prefix' => __( 'In', 'monstroid2' ),
				) );
				monstroid2_posted_on( array(
					'prefix' => __( 'Posted', 'monstroid2' )
				) );
			?></div><!-- .entry-meta -->
			<h4 class="entry-title"><?php 
				monstroid2_sticky_label();
				the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
			?></h4>
		</header><!-- .entry-header -->

		<?php monstroid2_post_excerpt(); ?>

		<footer class="entry-footer">
			<div class="entry-meta">
				<?php
					monstroid2_post_tags( array(
						'prefix' => __( 'Tags:', 'monstroid2' )
					) );
				?>
				<div><?php
					monstroid2_post_link();
				?></div>
			</div>
			<?php monstroid2_edit_link(); ?>
		</footer><!-- .entry-footer -->
	
	</div>

</article><!-- #post-<?php the_ID(); ?> -->
