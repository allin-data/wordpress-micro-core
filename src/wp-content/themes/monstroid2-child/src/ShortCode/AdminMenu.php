<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme\ShortCode;

/**
 * Class AdminMenu
 * @package AllInData\Dgr\Theme\ShortCode
 */
class AdminMenu implements ThemeShortCodeInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('dgr_admin_menu', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (is_admin()) {
            return;
        }
        get_template_part('templates/navigation/menu-admin');
    }
}