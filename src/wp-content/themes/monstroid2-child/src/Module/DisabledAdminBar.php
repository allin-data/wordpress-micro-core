<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme\Module;

/**
 * Class DisabledAdminBar
 * @package AllInData\Dgr\Theme\Module
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
        show_admin_bar(false);
    }
}