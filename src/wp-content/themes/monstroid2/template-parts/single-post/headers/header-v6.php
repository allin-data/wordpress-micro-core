<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Monstroid2
 */

$author_block_enabled = monstroid2_theme()->customizer->get_value( 'single_author_block' );

?>

<div class="single-header-6">
	<header class="entry-header">
		<div class="entry-meta">
			<?php
				monstroid2_posted_in( array(
					'delimiter' => '',
					'before'    => '<div class="cat-links btn-style">',
					'after'     => '</div>'
				) );
				if ( ! $author_block_enabled ) {
					monstroid2_posted_by();
					monstroid2_posted_on( array(
						'prefix'  => __( 'Posted', 'monstroid2' ),
					) );
				}
				monstroid2_post_comments( array(
					'postfix' => __( 'Comment(s)', 'monstroid2' ),
				) );
			?>
		</div><!-- .entry-meta -->
		<?php the_title( '<h1 class="entry-title h2-style">', '</h1>' ); ?>
	</header><!-- .entry-header -->
</div>