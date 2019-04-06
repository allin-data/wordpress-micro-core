<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Monstroid2
 */

?>

<footer class="entry-footer">
	<div class="entry-meta"><?php
		monstroid2_post_tags ( array(
			'prefix'    => __( 'Tags:', 'monstroid2' ),
			'delimiter' => ''
		) );
	?></div>
</footer><!-- .entry-footer -->