<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\Module;

/**
 * Class DisabledAdminBar
 * @package AllInData\MicroErp\Theme\Module
 */
class DisabledAdminBar implements ThemeModuleInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_action('init', [$this, 'disabledAdminBar']);
    }

    /**
     *
     */
    public function disabledAdminBar()
    {
        if (current_user_can('administrator')) {
            return;
        }
        show_admin_bar(false);
    }
}