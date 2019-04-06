<?php
/**
 * Login messages
 */

$message = wp_cache_get( 'jet-login-messages' );

if ( ! $message ) {
	return;
}

?>
<div class="jet-login-message"><?php
	echo $message;
?></div>