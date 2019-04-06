<?php
/**
 * Search form template
 */
$settings = $this->get_settings();
?>
<form role="search" method="get" class="jet-search__form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="jet-search__label">
		<input type="search" class="jet-search__field" placeholder="<?php echo esc_attr( $settings['search_placeholder'] ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<?php if ( 'true' ===  $settings['show_search_submit'] ) : ?>
	<button type="submit" class="jet-search__submit"><?php
		$this->__html( 'search_submit_icon', '<i class="jet-search__submit-icon %s"></i>' );
		$this->__html( 'search_submit_label', '<div class="jet-search__submit-label">%s</div>' );
	?></button>
	<?php endif; ?>
	<?php if ( isset( $settings['is_product_search'] ) && 'true' === $settings['is_product_search'] ) : ?>
		<input type="hidden" name="post_type" value="product" />
	<?php endif; ?>
</form>