<div class="jet-menu-settings-fields">
<?php

if ( count( $tagged_menu_locations ) == 1 ) {

	$locations = array_keys( $tagged_menu_locations );
	$location  = $locations[0];

	if ( isset( $tagged_menu_locations[ $location ] ) ) {
		include $settings_list;
	}

} else {

	echo '<div class="jet-menu-settings-locations">';

		foreach ( $theme_locations as $location => $name ) {

			if ( ! isset( $tagged_menu_locations[ $location ] ) ) {
				continue;
			}

			printf( '<h4 class="theme_settings">%s</h4>', esc_html( $name ) );

			echo '<div>';
			include $settings_list;
			echo '</div>';
		}

	echo '</div>';

}
?>
</div>

<?php submit_button( __( 'Save', 'jet-menu' ), 'jet-menu-settins-save button-primary alignright' ); ?>
<span class='spinner'></span>