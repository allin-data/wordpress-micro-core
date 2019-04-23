<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm;

use AllInData\MicroErp\Core\AbstractElementorPlugin;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\PluginInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Mdm\Model\Capability\CapabilityInterface;
use AllInData\MicroErp\Mdm\Model\Role\RoleInterface;

/**
 * Class Plugin
 * @package AllInData\MicroErp\Mdm
 */
class Plugin extends AbstractElementorPlugin implements PluginInterface
{
    /**
     * @var RoleInterface[]
     */
    private $roles;
    /**
     * @var CapabilityInterface[]
     */
    private $capabilities;

    /**
     * Plugin constructor.
     * @param string $templatePath
     * @param PluginModuleInterface[] $modules
     * @param PluginControllerInterface[] $controllers
     * @param PluginShortCodeInterface[] $shortCodes
     * @param ElementorWidgetInterface[] $widgets
     * @param RoleInterface[] $roles
     * @param CapabilityInterface[] $capabilities
     */
    public function __construct(
        string $templatePath,
        array $modules = [],
        array $controllers = [],
        array $shortCodes = [],
        array $widgets = [],
        array $roles = [],
        array $capabilities = []
    ) {
        parent::__construct($templatePath, $modules, $controllers, $shortCodes, $widgets);
        $this->roles = $roles;
        $this->capabilities = $capabilities;
    }

    /**
     * @inheritdoc
     */
    public function load()
    {
        register_activation_hook(AID_MICRO_ERP_MDM_FILE, [$this, 'installPlugin']);
        register_deactivation_hook(AID_MICRO_ERP_MDM_FILE, [$this, 'disablePlugin']);
    }

    /**
     * On plugin installation
     */
    public function installPlugin()
    {
        foreach ($this->roles as $role) {
            $role->installRole();
        }
        foreach ($this->capabilities as $capability) {
            $capability->install();
        }
    }

    /**
     * On plugin deactivation
     */
    public function disablePlugin()
    {
        foreach ($this->capabilities as $capability) {
            $capability->remove();
        }
        foreach ($this->roles as $role) {
            $role->removeRole();
        }
    }
}
