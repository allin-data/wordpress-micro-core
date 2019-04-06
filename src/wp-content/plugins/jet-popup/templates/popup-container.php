<?php
/**
 * Teamplte type popup
 */

$class_array = [
	'jet-popup',
	'jet-popup--front-mode',
	'jet-popup--hide-state',
	'jet-popup--animation-' . $popup_settings_main['jet_popup_animation'],
];

$class_attr = implode( ' ', $class_array );

?>
<div id="jet-popup-<?php echo $popup_id; ?>" class="<?php echo $class_attr; ?>" data-settings="<?php echo $popup_json_data; ?>">
	<div class="jet-popup__inner">
		<?php echo $overlay_html; ?>
		<div class="jet-popup__container">
			<div class="jet-popup__container-inner">
				<div class="jet-popup__container-overlay"></div>
				<div class="jet-popup__container-content"><?php
					if ( ! filter_var( $popup_settings_main['jet_popup_use_ajax'], FILTER_VALIDATE_BOOLEAN ) ) {
						$this->print_location_content( $popup_id );
					}
				?></div>
			</div>
			<?php echo $close_button_html; ?>
		</div>
	</div>
</div>
