<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/
?>
<?php if (current_user_can('administrator')): ?>
<div class="container-fluid">
    <div class="row">
        <?php if (has_nav_menu('admin-menu')) : ?>
            <nav id="site-navigation" class="admin-navigation"
                 aria-label="<?php esc_attr_e('Admin Menu', AID_DGR_THEME_TEXTDOMAIN); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'admin-menu',
                        'menu_class' => 'main-menu',
                        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    )
                );
                ?>
            </nav>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
