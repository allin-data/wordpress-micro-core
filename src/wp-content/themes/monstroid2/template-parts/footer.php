<?php
/**
 * The template for displaying the default footer layout.
 *
 * @package Monstroid2
 */
?>

<?php do_action( 'monstroid2-theme/widget-area/render', 'footer-area' ); ?>

<div <?php monstroid2_footer_class(); ?>>
	<div class="space-between-content"><?php
		monstroid2_footer_copyright();
		monstroid2_social_list( 'footer' );
	?></div>
</div><!-- .container -->
