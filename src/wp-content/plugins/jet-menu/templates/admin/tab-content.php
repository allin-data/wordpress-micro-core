<div class="jet-content-tab-wrap">
	<div class="jet-enabled-wrap"><?php
		echo $enabled;
	?></div>
	<div class="jet-edit-content-btn">
		<?php if ( jet_menu()->has_elementor() ) : ?>
		<button class="cherry5-ui-button cherry5-ui-button-success-style button-hero jet-menu-editor"><?php
			esc_html_e( 'Edit Mega Menu Item Content', 'jet-menu' );
		?></button>
		<?php else : ?>
		<p><?php
			esc_html_e( 'This plugin requires Elementor page builder to edt Mega Menu items content', 'jet-menu' );
		?></p>
		<?php endif; ?>
	</div>
</div>