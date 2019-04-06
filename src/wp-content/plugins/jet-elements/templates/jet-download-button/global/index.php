<?php
	$settings = $this->get_settings_for_display();
	$position = $settings['download_icon_position'];
?>
<a class="elementor-button elementor-size-md jet-download icon-position-<?php echo $position; ?>" href="<?php echo jet_elements_download_handler()->get_download_link( $settings['download_file'] ); ?>"><?php

	$format = '<i class="jet-download__icon %s icon-' . $position . '"></i>';

	$this->__html( 'download_icon', $format );

	$label    = $this->__get_html( 'download_label' );
	$sublabel = $this->__get_html( 'download_sub_label' );

	if ( $label || $sublabel ) {

		echo '<span class="jet-download__text">';

		printf(
			'<span class="jet-download__label">%s</span>',
			$this->__format_label( $label, $settings['download_file'] )
		);

		printf(
			'<small class="jet-download__sub-label">%s</small>',
			$this->__format_label( $sublabel, $settings['download_file'] )
		);

		echo '</span>';
	}

?></a>