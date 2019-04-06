<?php
/**
 * Loop item contnet
 */

if ( 'yes' !== $this->get_attr( 'show_excerpt' ) ) {
	$this->render_meta( 'content_related', 'jet-content-fields', array( 'before', 'after' ) );
	return;
}

$this->render_meta( 'content_related', 'jet-content-fields', array( 'before' ) );

jet_elements_post_tools()->get_post_content( array(
	'length'       => intval( $this->get_attr( 'excerpt_length' ) ),
	'content_type' => 'post_excerpt',
	'html'         => '<div %1$s>%2$s</div>',
	'class'        => 'entry-excerpt',
	'echo'         => true,
) );

$this->render_meta( 'content_related', 'jet-content-fields', array( 'after' ) );
