<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

get_header();
?>
<section id="primary" class="container-fluid fill-height">
    <div class="row fill-height">
        <?php
        get_template_part('templates/navigation/menu', 'sidebar');
        ?>
        <div class="col-md-10">
            <div>
                <main id="main" class="site-main">

                    <?php

                    /* Start the Loop */
                    while (have_posts()) :
                        the_post();

                        get_template_part('templates/content/content', 'single');

                        if (is_singular('attachment')) {
                            // Parent post navigation.
                            the_post_navigation(
                                array(
                                    /* translators: %s: parent post link */
                                    'prev_text' => sprintf(__('<span class="meta-nav">Published in</span><span class="post-title">%s</span>',
                                        AID_DGR_THEME_TEXTDOMAIN), '%title'),
                                )
                            );
                        } elseif (is_singular('post')) {
                            // Previous/next post navigation.
                            the_post_navigation(
                                array(
                                    'next_text' => '<span class="meta-nav" aria-hidden="true">' . __('Next Post',
                                            AID_DGR_THEME_TEXTDOMAIN) . '</span> ' .
                                        '<span class="screen-reader-text">' . __('Next post:',
                                            AID_DGR_THEME_TEXTDOMAIN) . '</span> <br/>' .
                                        '<span class="post-title">%title</span>',
                                    'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __('Previous Post',
                                            AID_DGR_THEME_TEXTDOMAIN) . '</span> ' .
                                        '<span class="screen-reader-text">' . __('Previous post:',
                                            AID_DGR_THEME_TEXTDOMAIN) . '</span> <br/>' .
                                        '<span class="post-title">%title</span>',
                                )
                            );
                        }

                        // If comments are open or we have at least one comment, load up the comment template.
                        if (comments_open() || get_comments_number()) {
                            comments_template();
                        }

                    endwhile; // End of the loop.
                    ?>
                </main>
            </div>
            <div>
                <?php
                get_footer();
                ?>
            </div>
        </div>
    </div>
</section>
