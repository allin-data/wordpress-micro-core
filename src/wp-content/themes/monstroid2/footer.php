<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Monstroid2
 */

?>

	</div><!-- #content -->

	<footer id="colophon" <?php echo monstroid2_get_container_classes( 'site-footer' ); ?>>
		<?php monstroid2_theme()->do_location( 'footer', 'template-parts/footer' ); ?>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
