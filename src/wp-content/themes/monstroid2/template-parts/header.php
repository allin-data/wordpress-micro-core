<?php
/**
 * Template part for default Header layout.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Monstroid2
 */
?>

<?php get_template_part( 'template-parts/top-panel' ); ?>

<div <?php monstroid2_header_class(); ?>>
	<?php do_action( 'monstroid2-theme/header/before' ); ?>
	<div class="space-between-content">
		<div <?php monstroid2_site_branding_class(); ?>>
			<?php monstroid2_header_logo(); ?>
		</div>
		<?php monstroid2_main_menu(); ?>
	</div>
	<?php do_action( 'monstroid2-theme/header/after' ); ?>
</div>
