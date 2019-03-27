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
    }

    /**
     *
     */
    public function addLogoutMenuEntry()
    {
        // add link to main menu, trigger logout action
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