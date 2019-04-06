<?php
/**
 * Loop start template
 */
$options = $this->get_advanced_carousel_options();
$dir = is_rtl() ? 'rtl' : 'ltr';

?>
<div class="jet-carousel elementor-slick-slider" data-slider_options="<?php echo htmlspecialchars( json_encode( $options ) ); ?>" dir="<?php echo $dir; ?>">