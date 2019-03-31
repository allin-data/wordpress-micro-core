<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms;

use AllInData\Dgr\Cms\Model\UserRole;
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

        register_activation_hook(AID_DGR_CMS_FILE, [$this, 'installPlugin']);
        register_deactivation_hook(AID_DGR_CMS_FILE, [$this, 'deinstallPlugin']);
    }

    /**
     * On plugin installation
     */
    public function installPlugin()
    {
        /** @var \WP_Role $parentRole */
        $parentRole = get_role('subscriber');
        add_role(UserRole::ROLE_LEVEL_USER_DEFAULT, __('Dgr User Default'), $parentRole->capabilities);
    }

    /**
     * On plugin deinstallation
     */
    public function deinstallPlugin()
    {
        remove_role(UserRole::ROLE_LEVEL_USER_DEFAULT);
    }

    /**
     * Add menu
     */
    public function addAdminMainMenu()
    {
        //
    }
}
