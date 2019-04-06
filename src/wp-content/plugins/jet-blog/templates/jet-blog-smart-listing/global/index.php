<?php
/**
 * Posts listing wrapper
 */
$settings = $this->__get_widget_settings();
$title    = $settings['block_title'];
$tag      = $settings['title_tag'];

?>
<div class="jet-smart-listing-wrap" data-settings='<?php $this->__export_settings(); ?>' data-page="1" data-term="0">
	<div class="jet-smart-listing__heading"><?php

		if ( $title ) {
			printf( '<%1$s class="jet-smart-listing__title">%2$s</%1$s>', $tag, $title );
		} else {
			echo '<span class="jet-smart-listing__title-placeholder"></span>';
		}

		$this->__get_filters();

	?></div>
	<div class="<?php $this->__listing_classes(); ?>"><?php
		$this->__render_posts();
	?></div>
	<?php $this->__get_arrows(); ?>
</div>
<div class="jet-smart-listing-loading"></div>