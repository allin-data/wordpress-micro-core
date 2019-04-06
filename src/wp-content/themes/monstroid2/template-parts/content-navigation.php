<?php
/**
 * Template part for posts navigation.
 *
 * @package Monstroid2
 */

do_action( 'monstroid2-theme/blog/posts-navigation-before' );

$navigation_type = monstroid2_theme()->customizer->get_value( 'blog_navigation_type' );

switch ( $navigation_type ) {
	case 'navigation':
		the_posts_navigation(
			apply_filters( 'monstroid2-theme/posts/navigation-args',
							array(
								'prev_text' => sprintf( '
									<span class="screen-reader-text">%1$s</span>
									<i class="fa fa-angle-left" aria-hidden="true"></i> %1$s',
									esc_html__( 'Older Posts', 'monstroid2' ) ),
								'next_text' => sprintf( '
									<span class="screen-reader-text">%1$s</span>
									%1$s <i class="fa fa-angle-right" aria-hidden="true"></i>',
									esc_html__( 'Newer Posts', 'monstroid2' ) ),
							)
			)
		);
		break;
	case 'pagination':
		the_posts_pagination( 
			apply_filters( 'monstroid2-theme/posts/pagination-args',
							array(
								'prev_text' => sprintf( '
									<span class="screen-reader-text">%1$s</span>
									<i class="fa fa-angle-left" aria-hidden="true"></i> %1$s',
									esc_html__( 'Prev', 'monstroid2' ) ),
								'next_text' => sprintf( '
									<span class="screen-reader-text">%1$s</span>
									%1$s <i class="fa fa-angle-right" aria-hidden="true"></i>',
									esc_html__( 'Next', 'monstroid2' ) ),
							)
			)
		);
		break;
}



do_action( 'monstroid2-theme/blog/posts-navigation-after' );
