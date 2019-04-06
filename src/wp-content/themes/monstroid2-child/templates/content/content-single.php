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
        <?php
        the_content(
            sprintf(
                wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Continue reading<span class="screen-reader-text"> "%s"</span>', AID_DGR_THEME_TEXTDOMAIN),
                    [
                        'span' => [
                            'class' => [],
                        ],
                    ]
                ),
                get_the_title()
            )
        );
        ?>
    </div>
    <?php if (!is_singular('attachment')) : ?>
        <?php get_template_part('templates/post/author', 'bio'); ?>
    <?php endif; ?>
</article>
