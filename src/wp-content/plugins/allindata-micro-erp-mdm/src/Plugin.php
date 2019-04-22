<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm;

use AllInData\MicroErp\Mdm\Model\UserRole;
use AllInData\MicroErp\Core\AbstractElementorPlugin;
use AllInData\MicroErp\Core\PluginInterface;

/**
 * Class Plugin
 * @package AllInData\MicroErp\Mdm
 */
class Plugin extends AbstractElementorPlugin implements PluginInterface
{
    /**
     * @inheritdoc
     */
    public function load()
    {
        register_activation_hook(AID_MICRO_ERP_MDM_FILE, [$this, 'installPlugin']);
        register_deactivation_hook(AID_MICRO_ERP_MDM_FILE, [$this, 'deinstallPlugin']);
    }

    /**
     * On plugin installation
     */
    public function installPlugin()
    {
        /** @var \WP_Role $parentRole */
        $parentRole = get_role('subscriber');
        add_role(UserRole::ROLE_LEVEL_USER_DEFAULT, __('Micro ERP User Default'), $parentRole->capabilities);
    }

    /**
     * On plugin deinstallation
     */
    public function deinstallPlugin()
    {
        remove_role(UserRole::ROLE_LEVEL_USER_DEFAULT);
        // @deprecated
        remove_role('dgr_acl_level_user_default');
    }
}
