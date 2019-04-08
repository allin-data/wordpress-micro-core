<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

?>

</div>

<footer id="colophon" <?php echo monstroid2_get_container_classes( 'site-footer' ); ?>>
    <?php monstroid2_theme()->do_location( 'footer', 'template-parts/footer' ); ?>
    <?php get_template_part('templates/footer/footer', 'widgets'); ?>
    <div class="row">
        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', AID_MICRO_ERP_THEME_TEXTDOMAIN); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_class' => 'footer',
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
