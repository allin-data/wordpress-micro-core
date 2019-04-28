<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme;

use AllInData\MicroErp\Theme\Module\ThemeModuleInterface;

/**
 * Class Theme
 * @package AllInData\MicroErp\Theme
 */
class Theme
{
    /**
     * @var ThemeModuleInterface[]
     */
    private $modules = [];

    /**
     * Theme constructor.
     * @param ThemeModuleInterface[] $modules
     */
    public function __construct(array $modules = [])
    {
        $this->modules = $modules;
    }

    /**
     * Initialize
     */
    public function init()
    {
        $this->initHooks();

        foreach ($this->modules as $module) {
            $module->init();
        }
    }

    /**
     * @return string
     */
    public function getThemeBaseUrl()
    {
        return get_template_directory_uri();
    }

    /**
     *
     */
    public function addNavigationMenus()
    {
        register_nav_menus(
            [
                'admin-menu' => __('Admin Menu'),
                'main' => __('Main Menu'),
                'footer' => __('Footer Menu'),
                'social' => __('Social Menu')
            ]
        );
    }

    /**
     *
     */
    public function addScripts()
    {
        wp_enqueue_script(
            'jquery-noconflict',
            get_theme_file_uri('js/jquery-noconflict.js'),
            [
                'jquery'
            ],
            '1.0'
        );
        wp_enqueue_script(
            'bootstrap-js',
            get_theme_file_uri('vendor/twbs/bootstrap/dist/js/bootstrap.js'),
            [
                'jquery'
            ],
            4.3,
            true
        );
        wp_enqueue_script(
            'chosen-js',
            get_theme_file_uri('node_modules/chosen-js/chosen.jquery.js'),
            [
                'jquery'
            ],
            '1.8.7',
            true
        );
    }

    /**
     *
     */
    public function addStylesToThemeEnqueue()
    {
        wp_enqueue_style(
            'fontawesome-free',
            get_stylesheet_directory_uri() . '/node_modules/@fortawesome/fontawesome-free/css/all.css',
            [],
            '5.8.1'
        );
        wp_enqueue_style(
            'monstroid2-child-theme-style',
            get_stylesheet_directory_uri() . '/style.css',
            [
                'monstroid2-theme-style',
                'fontawesome-free'
            ]
        );
        wp_enqueue_style(
            'chosen-js-style',
            get_stylesheet_directory_uri() . '/node_modules/chosen-js/chosen.css',
            [
                'monstroid2-child-theme-style',
                'fontawesome-free'
            ],
            '1.8.7'
        );
    }

    /**
     * Init hooks
     */
    private function initHooks()
    {
        add_action('wp_enqueue_scripts', [$this, 'addStylesToThemeEnqueue'], 999);
        add_action('wp_enqueue_scripts', [$this, 'addScripts'], 999);
        add_action('after_setup_theme', [$this, 'addNavigationMenus'], 10);
    }
}