<?php
/**
 * Template part for breadcrumbs.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Monstroid2
 */

$breadcrumbs_visibillity = monstroid2_theme()->customizer->get_value( 'breadcrumbs_visibillity' );

/**
 * [$breadcrumbs_visibillity description]
 * @var [type]
 */
$breadcrumbs_visibillity = apply_filters( 'monstroid2-theme/breadcrumbs/breadcrumbs-visibillity', $breadcrumbs_visibillity );

if ( ! $breadcrumbs_visibillity ) {
	return;
}

$breadcrumbs_front_visibillity = monstroid2_theme()->customizer->get_value( 'breadcrumbs_front_visibillity' );

if ( !$breadcrumbs_front_visibillity && is_front_page() ) {
	return;
}

do_action( 'monstroid2-theme/breadcrumbs/breadcrumbs-before-render' );

?><div <?php echo monstroid2_get_container_classes( 'site-breadcrumbs' ); ?>>
	<div <?php monstroid2_breadcrumbs_class(); ?>>
		<?php do_action( 'monstroid2-theme/breadcrumbs/before' ); ?>
		<?php do_action( 'cx_breadcrumbs/render' ); ?>
		<?php do_action( 'monstroid2-theme/breadcrumbs/after' ); ?>
	</div>
</div><?php

do_action( 'monstroid2-theme/breadcrumbs/breadcrumbs-after-render' );
