<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Monstroid2
 */
?>

<?php if ( is_active_sidebar( 'sidebar-shop' ) && 'none' !== monstroid2_theme()->sidebar_position ) : ?>
	<aside id="secondary" <?php monstroid2_secondary_content_class( array( 'widget-area' ) ); ?>>
	  <?php dynamic_sidebar( 'sidebar-shop' ); ?>
	</aside><!-- #secondary -->
<?php endif;