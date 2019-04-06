<?php
/**
 * Posts navigation template
 */
?>
<nav class="navigation posts-navigation" role="navigation">
	<div class="nav-links">
		<div class="nav-previous">
			<a href="#">
				<?php $this->__edit_html( 'prev_icon', jet_blog_tools()->get_carousel_arrow( '%s', 'prev' ) ); ?>
				<?php $this->__edit_html( 'prev_text' ); ?>
			</a>
		</div>
		<div class="nav-next">
			<a href="#">
				<?php $this->__edit_html( 'next_text' ); ?>
				<?php $this->__edit_html( 'next_icon', jet_blog_tools()->get_carousel_arrow( '%s', 'next' ) ); ?>
			</a>
		</div>
	</div>
</nav>
