<?php

add_action( 'init', 'monstroid2_plugins_wizard_config', 9 );

/**
 * Register Jet Plugins Wizards config
 * @return void
 */
function monstroid2_plugins_wizard_config() {

	if ( ! is_admin() ) {
		return;
	}

	if ( ! function_exists( 'jet_plugins_wizard_register_config' ) ) {
		return;
	}

	jet_plugins_wizard_register_config( array(
		'license' => array(
			'enabled' => true,
			'server'  => 'https://monstroid.zemez.io/',
			'item_id' => 9,
		),
		'plugins' => array(
			'get_from' => 'https://monstroid.zemez.io/wp-content/uploads/static/wizard-plugins.json',
		),
		'skins'   => array(
			'get_from' => 'https://monstroid.zemez.io/wp-content/uploads/static/wizard-skins.json',
		),
		'texts'   => array(
			'theme-name' => esc_html__( 'Monstroid2', 'monstroid2' ),
		)
	) );
}
