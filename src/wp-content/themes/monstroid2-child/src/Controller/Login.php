<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\Controller;

/**
 * Class Login
 * @package AllInData\MicroErp\Theme\Controller
 */
class Login implements ThemeControllerInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_action('wp_ajax_login', [$this, 'applyLogin']);
        add_action('wp_ajax_nopriv_login', [$this, 'applyLogin']);
    }

    /**
     *
     */
    public function applyLogin()
    {
        $credentials = [];
        $username = null;
        $password = null;

        $requestMethod = strtolower(filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        if ('post' === $requestMethod) {
            $username = filter_input(INPUT_POST, 'username');
            $password = filter_input(INPUT_POST, 'password');
        } else {
            $username = filter_input(INPUT_GET, 'username');
            $password = filter_input(INPUT_GET, 'password');
        }

        $credentials['user_login'] = $username;
        $credentials['user_password'] = $password;
        $credentials['remember'] = true;
        $user = wp_signon($credentials, false);
        if (is_wp_error($user)) {
            echo "FALSE";
        } else {
            echo "TRUE";
        }

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            die();
        } else {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        }
    }
}