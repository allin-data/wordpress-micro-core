<?php
/**
 * Images list item template
 */
$settings = $this->get_settings_for_display();
$perPage = $settings['per_page'];
$cover_icon = ! empty( $settings['cover_icon'] ) ? $settings['cover_icon'] : 'fa fa-search';
$is_more_button = $settings['view_more_button'];

$item_instance = 'item-instance-' . $this->item_counter;

$more_item = ( $this->item_counter >= $perPage && filter_var( $is_more_button, FILTER_VALIDATE_BOOLEAN ) ) ? true : false;

$this->add_render_attribute( $item_instance, 'class', array(
	'jet-portfolio__item',
	! $more_item ? 'visible-status' : 'hidden-status',
) );

if ( 'justify' == $settings['layout_type'] ) {
	$this->add_render_attribute( $item_instance, 'class', $this->get_justify_item_layout() );
}

$this->add_render_attribute( $item_instance, 'data-slug', $this->get_item_slug_list() );

$link_instance = 'link-instance-' . $this->item_counter;

$this->add_render_attribute( $link_instance, 'class', array(
	'jet-portfolio__link',
	// Ocean Theme lightbox compatibility
	class_exists( 'OCEANWP_Theme_Class' ) ? 'no-lightbox' : '',
) );

$this->add_render_attribute( $link_instance, 'href', $this->__loop_item( array( 'item_image', 'url' ), '%s' ) );
$this->add_render_attribute( $link_instance, 'data-elementor-open-lightbox', 'yes' );
$this->add_render_attribute( $link_instance, 'data-elementor-lightbox-slideshow', $this->get_id() );

?>
<article <?php echo $this->get_render_attribute_string( $item_instance ); ?>>
	<div class="jet-portfolio__inner">
		<a <?php echo $this->get_render_attribute_string( $link_instance ); ?>>
			<div class="jet-portfolio__image">
				<?php echo $this->__loop_image_item(); ?>
				<div class="jet-portfolio__image-loader"><span></span></div>
			</div>
		</a>
		<div class="jet-portfolio__content">
			<div class="jet-portfolio__content-inner"><?php
				$title_tag = $this->__get_html( 'title_html_tag', '%s' );
				echo $this->__loop_item( array( 'item_title' ), '<' . $title_tag . ' class="jet-portfolio__title">%s</' . $title_tag . '>' );
				echo $this->__get_item_category();
				echo $this->__loop_item( array( 'item_desc' ), '<p class="jet-portfolio__desc">%s</p>' );
				echo $this->__generate_item_button(); ?></div>
		</div>
	</div>
</article><?php

$this->item_counter++;
?>
