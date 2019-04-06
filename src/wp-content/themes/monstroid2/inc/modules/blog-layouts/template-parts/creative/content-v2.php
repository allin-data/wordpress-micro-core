<?php
/**
 * Template part for displaying creative posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Monstroid2
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item creative-item invert-hover' ); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="creative-item__thumbnail" <?php monstroid2_post_overlay_thumbnail( 'monstroid2-thumb-m' ); ?>></div>
	<?php endif; ?>

	<header class="entry-header">
		<?php
			monstroid2_posted_in();
			monstroid2_posted_on( array(
				'prefix' => __( 'Posted', 'monstroid2' )
			) );
		?>
		<h4 class="entry-title"><?php 
			monstroid2_sticky_label();
			the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
		?></h4>
	</header><!-- .entry-header -->

	<?php monstroid2_post_excerpt(); ?>

	<footer class="entry-footer">
		<div class="entry-meta">
			<div>
				<?php
					monstroid2_posted_by();
					monstroid2_post_comments( array(
						'prefix' => '<i class="fa fa-comment" aria-hidden="true"></i>'
					) );
					monstroid2_post_tags( array(
						'prefix' => __( 'Tags:', 'monstroid2' )
					) );
				?>
			</div>
			<?php
				monstroid2_post_link();
			?>
		</div>
		<?php monstroid2_edit_link(); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-<?php the_ID(); ?> -->
