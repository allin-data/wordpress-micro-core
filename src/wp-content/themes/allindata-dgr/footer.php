<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

?>

</div><!-- #content -->

<footer id="colophon" class="site-footer">
    <?php get_template_part('template-parts/footer/footer', 'widgets'); ?>
    <div class="site-info">
        <?php $blog_info = get_bloginfo('name'); ?>
        <?php if (!empty($blog_info)) : ?>
            <a class="site-name" href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>,
        <?php endif; ?>
        <?php
        if (function_exists('the_privacy_policy_link')) {
            the_privacy_policy_link('', '<span role="separator" aria-hidden="true"></span>');
        }
        ?>
        <?php if (has_nav_menu('footer')) : ?>
            <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', AID_DGR_THEME_TEXTDOMAIN); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'menu_class' => 'footer-menu',
                        'depth' => 1,
                    )
                );
                ?>
            </nav><!-- .footer-navigation -->
        <?php endif; ?>
    </div><!-- .site-info -->
</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
