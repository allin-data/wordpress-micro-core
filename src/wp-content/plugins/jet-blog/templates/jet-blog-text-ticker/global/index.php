<?php
/**
 * Smart tiles main template
 */

global $post;

$settings = $this->get_settings();

$typing = isset( $settings['typing_effect'] ) && 'yes' === $settings['typing_effect'] ? ' jet-use-typing' : '';

?>
<div class="jet-text-ticker">
	<?php $this->__get_current_date( $settings ); ?>
	<?php $this->__get_widget_title( $settings ); ?>
	<div class="jet-text-ticker__posts-wrap">
		<div class="jet-text-ticker__posts" <?php $this->__slider_atts(); ?> dir="ltr"><?php

			foreach ( $this->__get_query() as $post ) {

				setup_postdata( $post );
				?>
				<div class="jet-text-ticker__item">
					<div class="jet-text-ticker__item-content<?php echo $typing; ?>">
						<?php $this->__post_thumbnail( $settings ); ?>
						<?php $this->__post_author( $settings ); ?>
						<?php $this->__post_date( $settings ); ?>
						<div class="jet-text-ticker__item-typed-wrap"><a href="<?php the_permalink(); ?>" class="jet-text-ticker__item-typed"><div class="jet-text-ticker__item-typed-inner"><?php the_title(); ?></div></a></div>
					</div>
				</div>
				<?php
			}

			wp_reset_postdata();
		?></div>
	</div>
</div>