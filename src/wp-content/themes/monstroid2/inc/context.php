<?php
/**
 * Contextual functions for the header, footer, content and sidebar classes.
 *
 * @package Monstroid2
 */

/**
 * Retrieve a CSS class attribute for container based on `Page Layout Type` option.
 *
 * @since  1.0.0
 * @param  string  $classes Additional classes.
 * @return string
 */
function monstroid2_get_container_classes( $classes = null, $fullwidth = false ) {
	if ( $classes ) {
		$classes .= ' ';
	}

	if ( ! apply_filters( 'monstroid2-theme/site/fullwidth', $fullwidth ) ) {
		$layout_type = monstroid2_theme()->customizer->get_value( 'container_type' );

		if ( 'boxed' == $layout_type ) {
			$classes .= 'container';
		}
	}

	return 'class="' . $classes . '"';
}

/**
 * Prints site header container CSS classes
 *
 * @since   1.0.0
 * @param   string  $classes Additional classes.
 * @return  void
 */
function monstroid2_header_class( $classes = null ) {
	if ( $classes ) {
		$classes .= ' ';
	}

	$classes .= 'site-header__wrap';

	$site_header_container = apply_filters(
		'monstroid2-theme/site-header/container-enabled',
		true
	);

	if ( $site_header_container ) {
		$classes .= ' container';
	}

	$sticky = monstroid2_theme()->customizer->get_value( 'is_sticky_mode' );

	if ( $sticky ) {
		$classes .= 'header-sticky';
	}

	echo 'class="' . apply_filters( 'monstroid2-theme/site-header/content-classes', $classes ) . '"';
}

/**
 * Prints site breadcrumbs container CSS classes
 *
 * @since   1.0.0
 * @param   string  $classes Additional classes.
 * @return  void
 */
function monstroid2_breadcrumbs_class( $classes = null ) {
	if ( $classes ) {
		$classes .= ' ';
	}

	$classes .= 'site-breadcrumbs__wrap';

	$site_breadcrumbs_container = apply_filters(
		'monstroid2-theme/site-breadcrumbs/container-enabled',
		true
	);

	if ( $site_breadcrumbs_container ) {
		$classes .= ' container';
	}

	echo 'class="' . apply_filters( 'monstroid2-theme/site-breadcrumbs/content-classes', $classes ) . '"';
}

/**
 * Prints site content container CSS classes
 *
 * @since  1.0.0
 * @return string
 */
function monstroid2_content_class( $classes = null ) {
	if ( $classes ) {
		$classes .= ' ';
	}
	
	$classes .= 'site-content__wrap';

	$site_content_container = apply_filters( 'monstroid2-theme/site-content/container-enabled', true );

	if ( $site_content_container ) {
		$classes .= ' container';
	}

	echo 'class="' . apply_filters( 'monstroid2-theme/site-content/content-classes', $classes ) . '"';
}

/**
 * Prints site footer container CSS classes
 *
 * @since  1.0.0
 * @return string
 */
function monstroid2_footer_class( $classes = null ) {
	if ( $classes ) {
		$classes .= ' ';
	}
	
	$classes .= 'site-footer__wrap';

	$site_content_container = apply_filters( 'monstroid2-theme/site-footer/container-enabled', true );

	if ( $site_content_container ) {
		$classes .= ' container';
	}

	echo 'class="' . apply_filters( 'monstroid2-theme/site-footer/content-classes', $classes ) . '"';
}

/**
 * Prints primary content wrapper CSS classes.
 *
 * @since  1.0.0
 * @param  array $classes Additional classes.
 * @return void
 */
function monstroid2_primary_content_class( $classes = array() ) {
	echo monstroid2_get_layout_classes( 'content', $classes );
}

/**
 * Prints secondary content wrapper CSS classes.
 *
 * @since  1.0.0
 * @param  array $classes Additional classes.
 * @return void
 */
function monstroid2_secondary_content_class( $classes = array() ) {
	echo monstroid2_get_layout_classes( 'sidebar', $classes );
}

/**
 * Get CSS class attribute for passed layout context.
 *
 * @since  1.0.0
 * @param  string $layout  Layout context.
 * @param  array  $classes Additional classes.
 * @return string
 */
function monstroid2_get_layout_classes( $layout = 'content', $classes = array() ) {
	$sidebar_position = monstroid2_theme()->sidebar_position;
	$sidebar_width    = monstroid2_theme()->customizer->get_value( 'sidebar_width' );

	if ( 'none' === $sidebar_position || !is_active_sidebar( 'sidebar' ) ) {
		$sidebar_position = is_singular( 'post' ) ? 'single-post-fullwidth' : 'fullwidth';
		$sidebar_width = 0;
	}

	$layout_classes = ! empty( monstroid2_theme()->layout[ $sidebar_position ][ $sidebar_width ][ $layout ] ) ? monstroid2_theme()->layout[ $sidebar_position ][ $sidebar_width ][ $layout ] : array();

	if ( ! empty( $classes ) ) {
		$layout_classes = array_merge( $layout_classes, $classes );
	}

	if ( empty( $layout_classes ) ) {
		return '';
	}

	$layout_classes = apply_filters( "monstroid2-theme/wrapper/{$layout}_classes", $layout_classes );

	return 'class="' . join( ' ', $layout_classes ) . '"';
}

/**
 * Retrieve or print `class` attribute for Post List wrapper.
 *
 * @since  1.0.0
 * @param  string       $classes Additional classes.
 * @return string|void
 */
function monstroid2_posts_list_class( $classes = null ) {
	if ( $classes ) {
		$classes .= ' ';
	}

	$classes .= 'posts-list';

	echo 'class="' . apply_filters( 'monstroid2-theme/posts/list-class', $classes ) . '"';
}


/**
 * Prints site header CSS classes.
 *
 * @since  1.0.0
 * @param  array $classes Additional classes.
 * @return void
 */
function monstroid2_site_branding_class( $classes = array() ) {
	$classes[] = 'site-branding';

	echo 'class="' . join( ' ', $classes ) . '"';
}
