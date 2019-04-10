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
            array(
                'admin-menu' => __('Admin Menu'),
                'main' => __('Main Menu'),
                'footer' => __('Footer Menu'),
                'social' => __('Social Menu')
            )
        );
    }

    /**
     *
     */
    public function addScripts()
    {
        // do nothing for now
    }

    /**
     *
     */
    public function addStylesToThemeEnqueue()
    {
        wp_enqueue_style(
            'monstroid2-child-theme-style',
            get_stylesheet_directory_uri() . '/style.css',
            [
                'monstroid2-theme-style'
            ]
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