<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Report;

use AllInData\MicroErp\Core\AbstractElementorPlugin;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\PluginInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Mdm\Model\Capability\CapabilityInterface;

/**
 * Class Plugin
 * @package AllInData\MicroErp\Report
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
        register_activation_hook(AID_MICRO_ERP_REPORT_FILE, [$this, 'installPlugin']);
        register_deactivation_hook(AID_MICRO_ERP_REPORT_FILE, [$this, 'disablePlugin']);
        add_action('wp_enqueue_scripts', [$this, 'addStylesToThemeEnqueue'], 999);
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
    public function addStylesToThemeEnqueue()
    {
        if (is_admin()) {
            return;
        }

        wp_enqueue_style(
            'aid-micro-erp-report-c3-style',
            AID_MICRO_ERP_REPORT_URL . 'node_modules/c3/c3.css',
            [
                'monstroid2-child-theme-style'
            ],
            '0.7.0'
        );
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
            'aid-micro-erp-report-d3',
            AID_MICRO_ERP_REPORT_URL . 'node_modules/d3/dist/d3.js',
            [
                'jquery'
            ],
            '5.9.2',
            true
        );

        wp_enqueue_script(
            'aid-micro-erp-report-c3',
            AID_MICRO_ERP_REPORT_URL . 'node_modules/c3/c3.js',
            [
                'jquery',
                'aid-micro-erp-report-d3'
            ],
            '0.7.0',
            true
        );

        wp_enqueue_script(
            'aid-micro-erp-report-utilization',
            AID_MICRO_ERP_REPORT_URL . 'view/js/utilization-report.js',
            [
                'jquery',
                'aid-micro-erp-report-d3',
                'aid-micro-erp-report-c3'
            ],
            '1.0.0',
            true
        );
    }
}
