<?php
/**
 * Main Dropbar template
 */

$settings = $this->get_settings_for_display();

$this->add_render_attribute( 'wrapper', 'class', 'jet-dropbar' );
$this->add_render_attribute( 'wrapper', 'data-settings', esc_attr( $this->get_dropbar_export_settings() ) );

if ( isset( $settings['show_effect'] ) && $settings['show_effect'] ) {
	$this->add_render_attribute( 'wrapper', 'class', sprintf( 'jet-dropbar--%s-effect', esc_attr( $settings['show_effect'] ) ) );
}

if ( isset( $settings['fixed'] ) && filter_var( $settings['fixed'], FILTER_VALIDATE_BOOLEAN  ) ) {
	$this->add_render_attribute( 'wrapper', 'class', 'jet-dropbar-fixed' );

	if ( isset( $settings['fixed_position'] ) && $settings['fixed_position'] ) {
		$this->add_render_attribute( 'wrapper', 'class', sprintf( 'jet-dropbar-fixed--%s-position', esc_attr( $settings['fixed_position'] ) ) );
	}
}
?>

<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
	<div class="jet-dropbar__inner"><?php
		include $this->__get_global_template( 'button' );
		include $this->__get_global_template( 'content' );
	?></div>
</div>
