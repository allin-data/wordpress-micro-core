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
		<div class="creative-item__thumbnail" <?php monstroid2_post_overlay_thumbnail(); ?>></div>
	<?php endif; ?>

	<div class="container">

		<?php if ( monstroid2_theme()->customizer->get_value( 'blog_post_categories' ) ) : ?>
			<div class="creative-item__before-content"><?php
				monstroid2_posted_in( array(
					'prefix'    => '',
					'delimiter' => '',
					'before'    => '<div class="cat-links">',
					'after'     => '</div>'
				) );
			?></div>
		<?php endif; ?>

		<div class="creative-item__content">
			<header class="entry-header">
				<h3 class="entry-title"><?php 
					monstroid2_sticky_label();
					the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
				?></h3>
			</header><!-- .entry-header -->

			<?php monstroid2_post_excerpt(); ?>

			<footer class="entry-footer">
				<div class="entry-meta">
					<div>
						<?php
							monstroid2_posted_by();
							monstroid2_posted_on( array(
								'prefix' => __( 'Posted', 'monstroid2' )
							) );
							monstroid2_post_tags( array(
								'prefix' => __( 'Tags:', 'monstroid2' )
							) );
						?>
					</div>
					<?php
						monstroid2_post_comments( array(
							'postfix' => __( 'Comment(s)', 'monstroid2' )
						) );
					?>
				</div>
				<?php monstroid2_edit_link(); ?>
			</footer><!-- .entry-footer -->
		</div>

		<?php if ( 'none' !== monstroid2_theme()->customizer->get_value( 'blog_read_more_type' ) ) : ?>
			<div class="creative-item__after-content"><?php
				monstroid2_post_link();
			?></div>
		<?php endif; ?>

	</div>

</article><!-- #post-<?php the_ID(); ?> -->
