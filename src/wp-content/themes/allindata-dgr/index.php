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
                    <?php
                    if (have_posts()) {
                        ?>
                        <header class="entry-header">
                            <h1><?= __('News', AID_DGR_THEME_TEXTDOMAIN); ?></h1>
                        </header>
                        <?php

                        // Load posts loop.
                        while (have_posts()) {
                            the_post();
                            get_template_part('templates/content/content');
                        }

                    } else {

                        // If no content, include the "No posts found" template.
                        get_template_part('templates/content/content', 'none');

                    }
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
