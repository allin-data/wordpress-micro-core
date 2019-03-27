<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme\Module;

/**
 * Interface ThemeModuleInterface
 * @package AllInData\Dgr\Theme\Module
 */
class ForceLogin implements ThemeModuleInterface
{
    /*
     * URI Path
     */
    const REQUEST_URI_PATH_LOGIN = 'login';
    const WHITELIST_PATH_SET = [
        'wp-login.php',
        'wp-register.php',
        'wp-admin',
        'login',
        'register',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_action('init', [$this, 'addForceLogin']);
    }

    /**
     *
     */
    public function addForceLogin()
    {
        $relativeUrl = filter_input(INPUT_SERVER, 'REQUEST_URI');
        $path = parse_url($relativeUrl, PHP_URL_PATH);
        $currentPage = ltrim(rtrim($path, '/'), '/');
        if (!is_user_logged_in() && !in_array($currentPage, self::WHITELIST_PATH_SET)) {
            wp_redirect($this->getLoginUrl());
            exit();
        }
    }

    /**
     * @return string
     */
    private function getLoginUrl()
    {
        return get_site_url(null, self::REQUEST_URI_PATH_LOGIN);
    }
}