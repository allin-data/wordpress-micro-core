<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Module;

use AllInData\MicroErp\Auth\Controller\Login;
use AllInData\MicroErp\Auth\Controller\Logout;
use AllInData\MicroErp\Auth\Helper\LoginPageStateHelper;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;

/**
 * Class ForceLogin
 * @package AllInData\MicroErp\Planning\Module
 */
class ForceLogin implements PluginModuleInterface
{
    /*
     * URI Path
     */
    const REQUEST_URI_PATH_LOGIN = 'login';
    const REQUEST_URI_LOGIN_ACTION_ENDPOINT = 'wp-admin/admin-post.php';
    const REQUEST_URI_LOGOUT_ACTION_ENDPOINT = 'wp-login.php';
    const WHITELIST_PATH_SET = [
        'wp-login.php',
        'wp-register.php',
        'wp-admin'
    ];

    /**
     * @var LoginPageStateHelper
     */
    private $loginPageStateHelper;

    /**
     * ForceLogin constructor.
     * @param LoginPageStateHelper $loginPageStateHelper
     */
    public function __construct(LoginPageStateHelper $loginPageStateHelper)
    {
        $this->loginPageStateHelper = $loginPageStateHelper;
    }

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
        $currentPage = $this->getUrlPath(filter_input(INPUT_SERVER, 'REQUEST_URI'));
        $action = filter_input(INPUT_GET, 'action');
        if (!$action) {
            $action = filter_input(INPUT_POST, 'action');
        }

        // Login action is currently in progress, do not disturb
        if (Login::ACTION_SLUG === $action &&
            self::REQUEST_URI_LOGIN_ACTION_ENDPOINT === $currentPage) {
            return;
        }

        // Logout action is currently in progress, do not disturb
        if (Logout::ACTION_SLUG === $action &&
            self::REQUEST_URI_LOGOUT_ACTION_ENDPOINT === $currentPage) {
            return;
        }

        $currentPage = $this->getUrlPath(filter_input(INPUT_SERVER, 'REQUEST_URI'));
        $whitelistSet = array_merge(self::WHITELIST_PATH_SET, [$this->getUrlPath($this->getLoginUrl())]);
        if (!is_user_logged_in() && !in_array($currentPage, $whitelistSet)) {
            wp_redirect($this->getLoginUrl());
            exit();
        }
    }

    /**
     * @return string
     */
    private function getLoginUrl()
    {
        $loginPage = $this->loginPageStateHelper->getLoginPagePost();
        if (!$loginPage) {
            return get_site_url(null, self::REQUEST_URI_PATH_LOGIN);
        }
        return get_page_uri($loginPage->ID);
    }

    /**
     * @return string
     */
    private function getUrlPath($relativeUrl)
    {
        $path = parse_url($relativeUrl, PHP_URL_PATH);
        return ltrim(rtrim($path, '/'), '/');
    }
}