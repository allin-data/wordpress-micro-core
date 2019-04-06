<?php
/**
 * Loop item template
 */
$is_linked = $this->__is_linked();
$settings  = $this->get_settings();
?>
<div class="<?php echo $this->__get_logo_classes(); ?>">
<?php
if ( $is_linked ) {
	printf( '<a href="%1$s" class="jet-logo__link">', esc_url( home_url( '/' ) ) );
} else {
	echo '<div class="jet-logo__link">';
}

echo $this->__get_logo_image();
echo $this->__get_logo_text();

if ( $is_linked ) {
	echo '</a>';
} else {
	echo '</div>';
}
?>
</div>
