<?php
/**
 * User guide page heading
 */

if ( $title ) {
	?>
	<h4 class="jet-guide-title"><?php echo $title; ?></h4>
	<?php
}

if ( $content ) {
	?>
	<p class="jet-guide-content"><?php echo $content; ?></p>
	<?php
}