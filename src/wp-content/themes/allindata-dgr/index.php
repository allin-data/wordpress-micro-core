<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

get_header();
?>

    <section id="primary" class="container-fluid">
        <div class="row">
            <div class="col-xs-2">
                <?php
                get_template_part('templates/navigation/menu', 'main');
                ?>
            </div>
            <div class="col-xs-10">
                <div>
                    <main id="main" class="site-main">
                        <h1>TEST</h1>
                        <?php
                        /*
                        if ( have_posts() ) {

                            // Load posts loop.
                            while ( have_posts() ) {
                                the_post();
                                get_template_part( 'templates/content/content' );
                            }

                            // Previous/next page navigation.
                            twentynineteen_the_posts_navigation();

                        } else {

                            // If no content, include the "No posts found" template.
                            get_template_part( 'templates/content/content', 'none' );

                        }
                        */
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
