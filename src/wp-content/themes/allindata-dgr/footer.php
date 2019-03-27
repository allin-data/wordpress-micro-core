<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

?>

</div>

<footer id="colophon" class="site-footer">
    <?php get_template_part('templates/footer/footer', 'widgets'); ?>
    <div class="row">
        <?php if (has_nav_menu('footer-menu')) : ?>
            <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', AID_DGR_THEME_TEXTDOMAIN); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer-menu',
                        'menu_class' => 'footer-menu',
                        'depth' => 1,
                    )
                );
                ?>
            </nav>
        <?php endif; ?>
    </div>
</footer>

</div>

<?php wp_footer(); ?>

</body>
</html>
