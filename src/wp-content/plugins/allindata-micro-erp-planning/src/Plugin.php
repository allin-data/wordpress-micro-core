<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning;

use AllInData\MicroErp\Core\AbstractElementorPlugin;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\PluginInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Mdm\Model\Capability\CapabilityInterface;

/**
 * Class Plugin
 * @package AllInData\MicroErp\Planning
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
        register_activation_hook(AID_MICRO_ERP_PLANNING_FILE, [$this, 'installPlugin']);
        register_deactivation_hook(AID_MICRO_ERP_PLANNING_FILE, [$this, 'disablePlugin']);
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
            'tui-calendar',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/tui-calendar/dist/tui-calendar.css',
            [
                'monstroid2-child-theme-style'
            ],
            '1.12'
        );
        wp_enqueue_style(
            'tui-date-picker',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/tui-date-picker/dist/tui-date-picker.css',
            [
                'monstroid2-child-theme-style',
                'tui-calendar'
            ],
            '3.2.1'
        );
        wp_enqueue_style(
            'tui-time-picker',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/tui-time-picker/dist/tui-time-picker.css',
            [
                'monstroid2-child-theme-style',
                'tui-calendar'
            ],
            '1.5'
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
            'sprintf-js',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/sprintf-js/dist/sprintf.min.js',
            [
                'jquery'
            ],
            '1.1.2',
            true
        );
        wp_enqueue_script(
            'moment-js',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/moment/min/moment.min.js',
            [
                'jquery'
            ],
            '2.24.0',
            true
        );
        wp_enqueue_script(
            'tui-code-snippet',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/tui-code-snippet/dist/tui-code-snippet.js',
            [
                'jquery',
                'moment-js'
            ],
            '1.5',
            true
        );
        wp_enqueue_script(
            'tui-time-picker',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/tui-time-picker/dist/tui-time-picker.js',
            [
                'jquery',
                'moment-js',
                'tui-code-snippet'
            ],
            '1.5',
            true
        );
        wp_enqueue_script(
            'tui-date-picker',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/tui-date-picker/dist/tui-date-picker.js',
            [
                'jquery',
                'moment-js',
                'tui-code-snippet',
                'tui-time-picker'
            ],
            '3.2.1',
            true
        );
        wp_enqueue_script(
            'tui-calendar',
            AID_MICRO_ERP_PLANNING_URL . 'node_modules/tui-calendar/dist/tui-calendar.js',
            [
                'jquery',
                'moment-js',
                'tui-code-snippet',
                'tui-date-picker',
                'tui-time-picker'
            ],
            '1.12',
            true
        );

        wp_register_script(
            'aid-micro-erp-planning-calendar',
            AID_MICRO_ERP_PLANNING_URL . 'view/js/calendar.js',
            [
                'jquery',
                'moment-js',
                'tui-calendar'
            ],
            '1.0.1',
            true
        );
        wp_localize_script('aid-micro-erp-planning-calendar', 'wp_ajax_action', [
            'action_url' => admin_url('admin-ajax.php')
        ]);
        wp_enqueue_script('aid-micro-erp-planning-calendar');
    }
}
