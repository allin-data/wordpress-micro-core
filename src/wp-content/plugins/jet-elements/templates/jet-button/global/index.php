<?php
$settings = $this->get_settings_for_display();

$position = $this->get_settings_for_display( 'button_icon_position' );
$use_icon = $this->get_settings_for_display( 'use_button_icon' );
$hover_effect = $this->get_settings_for_display( 'hover_effect' );

$this->add_render_attribute( 'jet-button', 'class', 'jet-button__instance' );
$this->add_render_attribute( 'jet-button', 'class', 'jet-button__instance--icon-' . $position );
$this->add_render_attribute( 'jet-button', 'class', 'hover-' . $hover_effect );

$tag = 'div';

if ( ! empty( $settings['button_url']['url'] ) ) {
	$this->add_render_attribute( 'jet-button', 'href', $settings['button_url']['url'] );

	if ( $settings['button_url']['is_external'] ) {
		$this->add_render_attribute( 'jet-button', 'target', '_blank' );
	}

	if ( $settings['button_url']['nofollow'] ) {
		$this->add_render_attribute( 'jet-button', 'rel', 'nofollow' );
	}

	$tag = 'a';
}

?>
<div class="jet-button__container">
	<<?php echo $tag; ?> <?php echo $this->get_render_attribute_string( 'jet-button' ); ?>>
		<div class="jet-button__plane jet-button__plane-normal"></div>
		<div class="jet-button__plane jet-button__plane-hover"></div>
		<div class="jet-button__state jet-button__state-normal">
			<?php
				if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
					echo $this->__html( 'button_icon_normal', '<span class="jet-button__icon"><i class="%s"></i></span>' );
				}
				echo $this->__html( 'button_label_normal', '<span class="jet-button__label">%s</span>' );
			?>
		</div>
		<div class="jet-button__state jet-button__state-hover">
			<?php
				if ( filter_var( $use_icon, FILTER_VALIDATE_BOOLEAN ) ) {
					echo $this->__html( 'button_icon_hover', '<span class="jet-button__icon"><i class="%s"></i></span>' );
				}
				echo $this->__html( 'button_label_hover', '<span class="jet-button__label">%s</span>' );
			?>
		</div>
	</<?php echo $tag; ?>>
</div>
