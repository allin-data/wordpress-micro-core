<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme\Controller;

/**
 * Class Login
 * @package AllInData\Dgr\Theme\Controller
 */
class Logout implements ThemeControllerInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_action('wp_ajax_logout', [$this, 'applyLogout']);
        add_action('wp_ajax_nopriv_logout', [$this, 'applyLogout']);
        add_filter('wp_nav_menu_items', [$this, 'addLogoutMenuEntry'], 10, 2);
    }

    /**
     * add link to main menu, trigger logout action
     */
    public function addLogoutMenuEntry($items, $args)
    {
        $menuType = $args->theme_location ?: null;
        if (!is_user_logged_in() || $menuType !== 'main') {
            return $items;
        }
        $logoutUrl = wp_logout_url(get_permalink());
        $logoutItemEntry = '<li class="logout"><a href="' . $logoutUrl . '">' . __('Logout', AID_DGR_THEME_TEXTDOMAIN) . '</a></li>';
        $items = $items . $logoutItemEntry;
        return $items;
    }

    /**
     *
     */
    public function applyLogout()
    {
        wp_logout();

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            die();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
}