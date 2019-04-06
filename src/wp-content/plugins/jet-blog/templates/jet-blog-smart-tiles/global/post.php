<?php
/**
 * Post item template
 */
?>
<div class="jet-smart-tiles">
	<div class="jet-smart-tiles__box" <?php $this->__get_post_bg_attr(); ?>>
		<?php $this->__post_terms(); ?>
		<div class="jet-smart-tiles__box-content">
			<div class="jet-smart-tiles__box-content-inner">
				<?php include $this->__get_global_template( 'post-meta' ); ?>
				<?php $this->__render_meta( 'title_related', 'jet-title-fields', array( 'before' ) ); ?>
				<?php the_title( '<div class="jet-smart-tiles__box-title">', '</div>' ); ?>
				<?php $this->__render_meta( 'title_related', 'jet-title-fields', array( 'after' ) ); ?>
				<?php $this->__render_meta( 'content_related', 'jet-content-fields', array( 'before' ) ); ?>
				<?php $this->__post_excerpt( '<div class="jet-smart-tiles__box-excerpt">', '</div>' ); ?>
				<?php $this->__render_meta( 'content_related', 'jet-content-fields', array( 'after' ) ); ?>
			</div>
		</div>
		<a href="<?php the_permalink(); ?>" class="jet-smart-tiles__box-link"></a>
	</div>
</div>