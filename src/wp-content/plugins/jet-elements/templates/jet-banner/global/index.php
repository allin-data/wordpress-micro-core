<?php
/**
 * Loop item template
 */
?>
<figure class="jet-banner jet-effect-<?php $this->__html( 'animation_effect', '%s' ); ?>"><?php
	$target = $this->__get_html( 'banner_link_target', ' target="%s"' );

	$this->__html( 'banner_link', '<a href="%s" class="jet-banner__link"' . $target . '>' );
		echo '<div class="jet-banner__overlay"></div>';
		echo $this->__get_banner_image();
		echo '<figcaption class="jet-banner__content">';
			echo '<div class="jet-banner__content-wrap">';
				$title_tag = $this->__get_html( 'banner_title_html_tag', '%s' );

				$this->__html( 'banner_title', '<' . $title_tag  . ' class="jet-banner__title">%s</' . $title_tag  . '>' );
				$this->__html( 'banner_text', '<div class="jet-banner__text">%s</div>' );
			echo '</div>';
		echo '</figcaption>';
	$this->__html( 'banner_link', '</a>' );
?></figure>
