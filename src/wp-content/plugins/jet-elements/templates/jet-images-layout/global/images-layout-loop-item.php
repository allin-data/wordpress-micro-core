<?php
/**
 * Images list item template
 */
$settings = $this->get_settings_for_display();
$col_class = '';

if ( 'grid' === $settings['layout_type'] ) {
	$col_class = jet_elements_tools()->col_classes( array(
		'desk' => $this->__get_html( 'columns' ),
		'tab'  => $this->__get_html( 'columns_tablet' ),
		'mob'  => $this->__get_html( 'columns_mobile' ),
	) );
}

$link_instance = 'link-instance-' . $this->item_counter;

$link_type = $this->__loop_item( array( 'item_link_type' ), '%s' );

$this->add_render_attribute( $link_instance, 'class', array(
	'jet-images-layout__link',
	// Ocean Theme lightbox compatibility
	class_exists( 'OCEANWP_Theme_Class' ) ? 'no-lightbox' : '',
) );

if ( 'lightbox' === $link_type ) {
	$this->add_render_attribute( $link_instance, 'href', $this->__loop_item( array( 'item_image', 'url' ), '%s' ) );
	$this->add_render_attribute( $link_instance, 'data-elementor-open-lightbox', 'yes' );
	$this->add_render_attribute( $link_instance, 'data-elementor-lightbox-slideshow', $this->get_id()  );
} else {
	$target = $this->__loop_item( array( 'item_target' ), '%s' );
	$target = ! empty( $target ) ? $target : '_self';

	$this->add_render_attribute( $link_instance, 'href', $this->__loop_item( array( 'item_url' ), '%s' ) );
	$this->add_render_attribute( $link_instance, 'target', $target );
}

$this->item_counter++;


$image_format = '<img class="jet-images-layout__image-instance" src="%s" alt="">';

if ( 'justify' === $settings['layout_type'] ) {
	$image_format = '<img class="jet-images-layout__image-instance" src="%1$s" data-width="%2$s" data-height="%3$s" alt="">';
}

?>
<div class="jet-images-layout__item <?php echo $col_class ?>">
	<div class="jet-images-layout__inner">
		<div class="jet-images-layout__image-loader"><span></span></div>
		<a <?php echo $this->get_render_attribute_string( $link_instance ); ?>>
			<div class="jet-images-layout__image">
				<?php echo $this->__loop_image_item( 'item_image', $image_format ); ?>
			</div>
			<div class="jet-images-layout__content">
					<?php
						echo $this->__loop_item( array( 'item_icon' ), '<div class="jet-images-layout__icon"><div class="jet-images-layout-icon-inner"><i class="%s"></i></div></div>' );
					?>

					<?php
						$title_tag = $this->__get_html( 'title_html_tag', '%s' );

						echo $this->__loop_item( array( 'item_title' ), '<' . $title_tag . ' class="jet-images-layout__title">%s</' . $title_tag . '>' );
						echo $this->__loop_item( array( 'item_desc' ), '<div class="jet-images-layout__desc">%s</div>' );
					?>
			</div>
		</a>
	</div>
</div>
