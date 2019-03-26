<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms;

use AllInData\Dgr\Core\AbstractPlugin;
use AllInData\Dgr\Core\PluginInterface;

/**
 * Class Plugin
 * @package AllInData\Dgr\Cms
 */
class Plugin extends AbstractPlugin implements PluginInterface
{
    /**
     * @inheritdoc
     */
    public function load()
    {
        // Administration menu
        add_action('admin_menu', [$this, 'addAdminMainMenu'], 9, 0);
        add_action('plugins_loaded', array(self::class, 'init'), 999);
    }

    /**
     * Add menu
     */
    public function addAdminMainMenu()
    {
        //
    }
}
