<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

?>
<div class="container-fluid">
    <div class="row">
        <?php if (has_nav_menu('main-menu')) : ?>
            <nav id="site-navigation" class="main-navigation"
                 aria-label="<?php esc_attr_e('Top Menu', AID_DGR_THEME_TEXTDOMAIN); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'main-menu',
                        'menu_class' => 'main-menu',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    )
                );
                ?>
            </nav>
        <?php endif; ?>
    </div>
</div>
