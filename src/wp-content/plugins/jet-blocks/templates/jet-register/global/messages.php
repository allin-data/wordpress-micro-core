<?php
/**
 * Registration messages
 */

$message = wp_cache_get( 'jet-register-messages' );

if ( ! $message ) {
	return;
}

?>
<div class="jet-register-message"><?php
	echo $message;
?></div>