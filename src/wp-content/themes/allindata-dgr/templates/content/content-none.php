<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

?>
<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php _e('Nothing Found', AID_DGR_THEME_TEXTDOMAIN); ?></h1>
    </header>

    <div class="page-content">
        <?php
        if (is_search()) :
            ?>

            <p><?php _e('Sorry, but nothing matched your search terms. Please try again with some different keywords.',
                    AID_DGR_THEME_TEXTDOMAIN); ?></p>
            <?php
            get_search_form();

        else :
            ?>

            <p><?php _e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.',
                    AID_DGR_THEME_TEXTDOMAIN); ?></p>
            <?php
            get_search_form();

        endif;
        ?>
    </div>
</section>
