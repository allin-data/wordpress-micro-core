<?php
/**
 * Posts loop start template
 */

$classes = array(
	'jet-posts',
	'col-row',
	jet_elements_tools()->gap_classes( $this->get_attr( 'columns_gap' ), $this->get_attr( 'rows_gap' ) ),
);

$equal = $this->get_attr( 'equal_height_cols' );

if ( $equal ) {
	$classes[] = 'jet-equal-cols';
}

?>
<div class="<?php echo implode( ' ', $classes ); ?>">