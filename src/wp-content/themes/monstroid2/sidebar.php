<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Monstroid2
 */

?>

<?php 

do_action( 'monstroid2-theme/sidebar/before' );

if ( is_active_sidebar( 'sidebar' ) && 'none' !== monstroid2_theme()->sidebar_position ) : ?>
	<aside id="secondary" <?php monstroid2_secondary_content_class( array( 'widget-area' ) ); ?>>
		<?php dynamic_sidebar( 'sidebar' ); ?>
	</aside><!-- #secondary -->
<?php endif; 

do_action( 'monstroid2-theme/sidebar/after' );
