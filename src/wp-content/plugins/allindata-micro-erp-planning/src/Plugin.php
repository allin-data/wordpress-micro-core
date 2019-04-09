<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning;

use AllInData\MicroErp\Core\AbstractElementorPlugin;
use AllInData\MicroErp\Core\PluginInterface;

/**
 * Class Plugin
 * @package AllInData\MicroErp\Planning
 */
class Plugin extends AbstractElementorPlugin implements PluginInterface
{
    /**
     * @inheritdoc
     */
    public function load()
    {
        // Administration menu
        add_action('plugins_loaded', array(self::class, 'init'), 999);
        add_action('wp_enqueue_scripts', [$this, 'addStylesToThemeEnqueue'], 999);
        add_action('wp_enqueue_scripts', [$this, 'addScripts'], 999);
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
            AID_MICRO_ERP_PLANNING_URL . '/node_modules/tui-calendar/dist/tui-calendar.css',
            [
                'monstroid2-child-theme-style'
            ],
            '1.11'
        );
        wp_enqueue_style(
            'tui-date-picker',
            AID_MICRO_ERP_PLANNING_URL . '/node_modules/tui-date-picker/dist/tui-date-picker.css',
            [
                'monstroid2-child-theme-style',
                'tui-calendar'
            ],
            '3.2.1'
        );
        wp_enqueue_style(
            'tui-time-picker',
            AID_MICRO_ERP_PLANNING_URL . '/node_modules/tui-time-picker/dist/tui-time-picker.css',
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
            'tui-code-snippet',
            AID_MICRO_ERP_PLANNING_URL . '/node_modules/tui-code-snippet/dist/tui-code-snippet.js',
            [
                'jquery'
            ],
            '1.5',
            true
        );
        wp_enqueue_script(
            'tui-calendar',
            AID_MICRO_ERP_PLANNING_URL . '/node_modules/tui-calendar/dist/tui-calendar.js',
            [
                'jquery',
                'tui-code-snippet'
            ],
            '1.11',
            true
        );
        wp_enqueue_script(
            'tui-date-picker',
            AID_MICRO_ERP_PLANNING_URL . '/node_modules/tui-date-picker/dist/tui-date-picker.js',
            [
                'jquery',
                'tui-calendar'
            ],
            '3.2.1',
            true
        );
        wp_enqueue_script(
            'tui-time-picker',
            AID_MICRO_ERP_PLANNING_URL . '/node_modules/tui-time-picker/dist/tui-time-picker.js',
            [
                'jquery',
                'tui-calendar'
            ],
            '1.5',
            true
        );
    }
}
