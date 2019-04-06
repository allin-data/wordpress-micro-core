<?php
/**
 * Template part for displaying creative posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Monstroid2
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item creative-item' ); ?>>

	<?php if ( monstroid2_theme()->customizer->get_value( 'blog_post_publish_date' ) ) : ?>
		<div class="creative-item__before-content">
			<?php
				$day = get_the_date('d');
				$month = get_the_date('m');
			?>
			<div class="posted-on">
				<span class="posted-on__day"><?php echo esc_html( $day ); ?></span><span class="posted-on__month">/<?php echo esc_html( $month ); ?></span>
			</div>
		</div>
	<?php endif; ?>

	<div class="creative-item__content">

		<?php monstroid2_post_thumbnail( 'thumbnail' ); ?>

		<div class="creative-item__content-wrapper">
			<header class="entry-header">
				<div class="entry-meta">
					<?php
						monstroid2_posted_by();
						monstroid2_posted_in( array(
							'prefix' => __( 'In', 'monstroid2' ),
						) );
					?>
				</div><!-- .entry-meta -->
				<h3 class="entry-title"><?php 
					monstroid2_sticky_label();
					the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a>' );
				?></h3>
			</header><!-- .entry-header -->

			<?php monstroid2_post_excerpt(); ?>

			<footer class="entry-footer">
				<div class="entry-meta"><?php
					monstroid2_post_tags( array(
						'prefix' => __( 'Tags:', 'monstroid2' )
					) );
					monstroid2_post_comments( array(
						'postfix' => __( 'Comment(s)', 'monstroid2' )
					) );
				?></div>
				<?php monstroid2_edit_link(); ?>
			</footer><!-- .entry-footer -->
		</div>

	</div>

	<?php if ( 'none' !== monstroid2_theme()->customizer->get_value( 'blog_read_more_type' ) ) : ?>
		<div class="creative-item__after-content"><?php
			monstroid2_post_link();
		?></div>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
