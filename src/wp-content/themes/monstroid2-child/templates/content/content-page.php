<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-header">
		<h1><?= $wp_query->post->post_title; ?></h1>
	</div>
	<div class="entry-content">
		<?php the_content(); ?>
	</div>
</article>
