<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme;

/**
 * Class Theme
 * @package AllInData\Dgr\Theme
 */
class Theme
{

    /**
     * Initialize
     */
    public function init()
    {
        $this->initHooks();
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
                'main-menu' => __( 'Main Menu' ),
                'footer-menu' => __( 'Footer Menu' )
            )
        );
    }

    /**
     *
     */
    public function addScripts()
    {
        wp_enqueue_script(
            'bootstrap-js',
            $this->getThemeBaseUrl() . '/vendor/twbs/bootstrap/dist/js/bootstrap.js',
            ['jquery'],
            1.1,
            true
        );
    }

    /**
     *
     */
    public function addStylesToThemeEnqueue()
    {
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', []);
    }

    /**
     * Init hooks
     */
    private function initHooks()
    {
        add_action('wp_enqueue_scripts', [$this, 'addStylesToThemeEnqueue']);
        add_action('wp_enqueue_scripts', [$this, 'addScripts']);
        add_action('init', [$this, 'addNavigationMenus']);
    }
}