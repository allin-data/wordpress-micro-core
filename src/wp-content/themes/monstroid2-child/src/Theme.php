<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme;

use AllInData\Dgr\Theme\Controller\ThemeControllerInterface;
use AllInData\Dgr\Theme\Module\ThemeModuleInterface;
use AllInData\Dgr\Theme\ShortCode\ThemeShortCodeInterface;

/**
 * Class Theme
 * @package AllInData\Dgr\Theme
 */
class Theme
{
    /**
     * @var ThemeModuleInterface[]
     */
    private $modules = [];
    /**
     * @var ThemeControllerInterface[]
     */
    private $controllers = [];
    /**
     * @var ThemeShortCodeInterface[]
     */
    private $shortCodes = [];

    /**
     * Theme constructor.
     * @param ThemeModuleInterface[] $modules
     * @param ThemeControllerInterface[] $controllers
     * @param ThemeShortCodeInterface[] $shortCodes
     */
    public function __construct(array $modules = [], array $controllers = [], array $shortCodes = [])
    {
        $this->modules = $modules;
        $this->controllers = $controllers;
        $this->shortCodes = $shortCodes;
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

        foreach ($this->controllers as $controller) {
            $controller->init();
        }

        foreach ($this->shortCodes as $shortCode) {
            $shortCode->init();
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
//        wp_enqueue_script(
//            'bootstrap-js',
//            $this->getThemeBaseUrl() . '/vendor/twbs/bootstrap/dist/js/bootstrap.js',
//            ['jquery'],
//            1.1,
//            true
//        );
    }

    /**
     *
     */
    public function addStylesToThemeEnqueue()
    {
        wp_enqueue_style('monstroid2-parent-theme-style', get_stylesheet_directory_uri() . '/style.css', []);
    }

    /**
     * Init hooks
     */
    private function initHooks()
    {
        add_action('wp_enqueue_scripts', [$this, 'addStylesToThemeEnqueue']);
        add_action('wp_enqueue_scripts', [$this, 'addScripts']);
        add_action('after_setup_theme', [$this, 'addNavigationMenus'], 10);
    }
}