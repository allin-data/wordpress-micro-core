<?php
/**
 * Import-export template
 */
?>
<div class="jet-menu-import-export">
	<a href="<?php echo jet_menu_option_page()->export_url(); ?>" class="cherry5-ui-button cherry5-ui-button-normal-style ui-button jet-menu-export-btn">
		<?php esc_html_e( 'Export', 'jet-menu' ); ?>
	</a>
	<button type="button" class="cherry5-ui-button cherry5-ui-button-normal-style ui-button jet-menu-import-btn">
		<?php esc_html_e( 'Import', 'jet-menu' ); ?>
	</button>
	<div class="jet-menu-import">
		<div class="jet-menu-import__inner">
			<input type="file" class="jet-menu-import-file" accept="application/json" multiple="false">
			<button type="button" class="cherry5-ui-button cherry5-ui-button-normal-style ui-button jet-menu-run-import-btn">
				<span class="text"><?php esc_html_e( 'Go', 'jet-menu' ); ?></span><span class="loader-wrapper"><span class="loader"></span></span><span class="dashicons dashicons-yes icon"></span>
			</button>
			<div class="jet-menu-import-messages"></div>
		</div>
	</div>
</div>
