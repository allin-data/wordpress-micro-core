<?php
/**
 * Library tabs template
 */
?>
<div class="nav-tab-wrapper jet-library-tabs"><?php
	foreach ( $tabs as $tab => $label ) {

		$class = 'nav-tab';

		if ( $tab === $active_tab ) {
			$class .= ' nav-tab-active';
		}

		if ( 'all' !== $tab ) {
			$link = add_query_arg( array( $this->type_tax => $tab ), $page_link );
		} else {
			$link = $page_link;
		}

		printf( '<a href="%1$s" class="%3$s">%2$s</a>', $link, $label, $class );

	}
?></div>
<br>