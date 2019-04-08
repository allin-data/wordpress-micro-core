<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\ShortCode;

/**
 * Class AdminMenu
 * @package AllInData\MicroErp\Theme\ShortCode
 */
class AdminMenu implements ThemeShortCodeInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_admin_menu', [$this, 'addShortCode']);
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