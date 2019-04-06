<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Monstroid2
 */

$has_post_thumbnail = has_post_thumbnail();
$has_post_thumbnail_class = $has_post_thumbnail ? 'has-post-thumbnail' : '';

?>

<div class="single-header-10 invert <?php echo esc_attr( $has_post_thumbnail_class ); ?>">
	<?php monstroid2_post_thumbnail( 'monstroid2-thumb-xl', array( 'link' => false ) ); ?>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title h3-style">', '</h1>' ); ?>
					<div class="entry-header-bottom">
						<div class="entry-meta"><?php
							if ( monstroid2_theme()->customizer->get_value( 'single_post_author' ) ) : ?>
								<span class="post-author">
									<span class="post-author__avatar"><?php
										monstroid2_get_post_author_avatar( array(
											'size' => 50
										) );
									?></span>
									<?php monstroid2_posted_by();
								?></span>
							<?php endif; ?>
							<?php
								monstroid2_posted_in( array(
									'prefix'  => __( 'In', 'monstroid2' ),
								) );
								monstroid2_posted_on( array(
									'prefix'  => __( 'Posted', 'monstroid2' ),
								) );
						?></div><!-- .entry-meta -->
						<div class="entry-meta"><?php
							monstroid2_post_comments( array(
								'prefix' => '<i class="fa fa-comment" aria-hidden="true"></i>',
								'class'  => 'comments-button'
							) );
						?></div><!-- .entry-meta -->
					</div>
				</header><!-- .entry-header -->
			</div>
		</div>
	</div>
</div>

