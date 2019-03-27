<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

get_header();
?>
<section id="primary" class="container-fluid fill-height">
    <div class="row fill-height">
        <div class="col-md-2 d-md-none">
            <?php
            get_template_part('templates/navigation/menu', 'main');
            get_template_part('templates/navigation/menu', 'admin');
            ?>
        </div>
        <div class="col-md-2 fill-height d-none d-md-block">
            <?php
            get_template_part('templates/navigation/menu', 'main');
            get_template_part('templates/navigation/menu', 'admin');
            ?>
        </div>
        <div class="col-md-10">
            <div>
                <main id="main" class="site-main">

                    <?php if (have_posts()) : ?>

                        <header class="page-header">
                            <h1 class="page-title">
                                <?php _e('Search results for:', AID_DGR_THEME_TEXTDOMAIN); ?>
                            </h1>
                            <div class="page-description"><?php echo get_search_query(); ?></div>
                        </header><!-- .page-header -->

                        <?php
                        // Start the Loop.
                        while (have_posts()) :
                            the_post();

                            /*
                             * Include the Post-Format-specific template for the content.
                             * If you want to override this in a child theme, then include a file
                             * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                             */
                            get_template_part('templates/content/content', 'excerpt');

                            // End the loop.
                        endwhile;

                    // If no content, include the "No posts found" template.
                    else :
                        get_template_part('templates/content/content', 'none');

                    endif;
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
