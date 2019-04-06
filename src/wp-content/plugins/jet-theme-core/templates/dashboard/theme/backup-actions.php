<?php
/**
 * Backup actions
 */

$this->builder->register_control(
	array(
		'jet_core_auto_backup' => array(
			'type'        => 'switcher',
			'title'       => esc_html__( 'Enable automatic backup', 'jet-theme-core' ),
			'description' => esc_html__( 'If On - new theme backup will be automaticcally created before each update', 'jet-theme-core' ),
			'value'       => jet_theme_core()->settings->get( 'auto_backup', 'true' ),
			'toggle'      => array(
				'true_toggle'  => 'On',
				'false_toggle' => 'Off',
			),
		),
	)
);
$create_backup_link = add_query_arg(
	array(
		'jet_action' => $this->get_slug(),
		'handle'     => 'create_backup',
	),
	admin_url( 'admin.php' )
);

$create_backup = sprintf(
	'<a href="%1$s" class="cx-button cx-button-normal-style">%2$s</a>',
	$create_backup_link,
	__( 'Create Backup', 'jet-theme-core' )
);

$this->builder->register_html(
	array(
		'jet_core_create_backup' => array(
			'type'  => 'html',
			'class' => 'cx-control',
			'html'  => $create_backup,
		),
	)
);

$this->builder->render();
