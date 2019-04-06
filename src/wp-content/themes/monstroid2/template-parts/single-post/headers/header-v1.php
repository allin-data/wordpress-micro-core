<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Monstroid2
 */

?>

<header class="entry-header">
	<?php the_title( '<h1 class="entry-title h2-style">', '</h1>' ); ?>
	<div class="entry-meta">
		<?php
			monstroid2_posted_by();
			monstroid2_posted_in( array(
				'prefix'  => __( 'In', 'monstroid2' ),
			) );
			monstroid2_posted_on( array(
				'prefix'  => __( 'Posted', 'monstroid2' ),
			) );
			monstroid2_post_comments( array(
				'postfix' => __( 'Comment(s)', 'monstroid2' ),
			) );
		?>
	</div><!-- .entry-meta -->
</header><!-- .entry-header -->

<?php monstroid2_post_thumbnail( 'monstroid2-thumb-l', array( 'link' => false ) ); ?>