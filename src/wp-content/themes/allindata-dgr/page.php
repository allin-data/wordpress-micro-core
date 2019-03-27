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

                        get_template_part('templates/content/content', 'page');

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
