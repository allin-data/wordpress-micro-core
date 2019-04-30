<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource;

use AllInData\MicroErp\Core\AbstractElementorPlugin;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\PluginInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Mdm\Model\Capability\CapabilityInterface;

/**
 * Class Plugin
 * @package AllInData\MicroErp\Resource
 */
class Plugin extends AbstractElementorPlugin implements PluginInterface
{
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
     * @param CapabilityInterface[] $capabilities
     */
    public function __construct(
        string $templatePath,
        array $modules = [],
        array $controllers = [],
        array $shortCodes = [],
        array $widgets = [],
        array $capabilities = []
    ) {
        parent::__construct($templatePath, $modules, $controllers, $shortCodes, $widgets);
        $this->capabilities = $capabilities;
    }

    /**
     * @inheritdoc
     */
    public function load()
    {
        register_activation_hook(AID_MICRO_ERP_RESOURCE_FILE, [$this, 'installPlugin']);
        register_deactivation_hook(AID_MICRO_ERP_RESOURCE_FILE, [$this, 'disablePlugin']);
        add_action('wp_enqueue_scripts', [$this, 'addScripts'], 999);
    }

    /**
     * On plugin installation
     */
    public function installPlugin()
    {
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
    }

    /**
     *
     */
    public function addScripts()
    {
        if (is_admin()) {
            return;
        }

        wp_enqueue_script(
            'aid-micro-erp-resource-sortable',
            AID_MICRO_ERP_RESOURCE_URL . 'view/admin/js/sortable.js',
            [
                'jquery'
            ],
            '1.0.0',
            true
        );

        wp_register_script(
            'aid-micro-erp-resource-type-attribute',
            AID_MICRO_ERP_RESOURCE_URL . 'view/admin/js/resource-type-attribute.js',
            [
                'jquery'
            ],
            '1.0.0',
            true
        );
        wp_localize_script('aid-micro-erp-resource-type-attribute', 'wp_ajax_action', [
            'action_url' => admin_url('admin-ajax.php')
        ]);
        wp_enqueue_script('aid-micro-erp-resource-type-attribute');
    }
}
