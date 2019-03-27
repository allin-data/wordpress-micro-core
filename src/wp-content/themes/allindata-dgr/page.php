<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

get_header();
?>

    <section id="primary" class="container">
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
    </section>

<?php
get_footer();
