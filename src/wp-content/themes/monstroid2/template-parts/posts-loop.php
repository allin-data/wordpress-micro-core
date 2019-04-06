<?php
/**
 * Posts loop template
 */

do_action( 'monstroid2-theme/blog/before' );

?><div <?php monstroid2_posts_list_class(); ?>><?php

	while ( have_posts() ) : the_post();

		/*
		* Include the Post-Format-specific template for the content.
		* If you want to override this in a child theme, then include a file
		* called content-___.php (where ___ is the Post Format name) and that will be used instead.
		*/
		get_template_part( monstroid2_get_post_template_part_slug(), monstroid2_get_post_style() );

	endwhile;

?></div><?php

do_action( 'monstroid2-theme/blog/after' );

get_template_part( 'template-parts/content', 'navigation' );
