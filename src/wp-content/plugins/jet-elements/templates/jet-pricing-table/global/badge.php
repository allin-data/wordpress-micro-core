<?php
/**
 * Featured badge template
 */

$badge = $this->get_settings_for_display( 'featured_badge' );

if ( isset( $badge['url'] ) ) {
	echo jet_elements_tools()->get_image_by_url( $badge['url'], array( 'class' => 'pricing-table__badge' ) );
}