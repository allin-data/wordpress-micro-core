<?php
/**
 * Auth Links Template
 */

$settings = $this->get_settings();
$order    = isset( $settings['order'] ) ? esc_attr( $settings['order'] ) : 'login_register';

switch ( $order ) {
	case 'register_login':
		$templates_order = array( 'register', 'registered', 'login', 'logout' );
		break;

	default:
		$templates_order = array( 'login', 'logout', 'register', 'registered' );
		break;
}

?>
<div class="jet-auth-links"><?php
	foreach ( $templates_order as $template ) {
		include $this->__get_global_template( $template );
	}
?></div>