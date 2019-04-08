<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\Module;

/**
 * Class ForceLogin
 * @package AllInData\MicroErp\Theme\Module
 */
class ForceLogin implements ThemeModuleInterface
{
    /*
     * URI Path
     */
    const REQUEST_URI_PATH_LOGIN = 'login';
    const SHORTCODE_LOGIN_FORM = '[micro_erp_login_form]';
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
        add_action('display_post_states', [$this, 'addLoginPageState'], 10, 2);
        add_action('init', [$this, 'addForceLogin']);
    }

    /**
     * @param array $postStates
     * @param \WP_Post $post
     * @return array
     */
    public function addLoginPageState($postStates, $post)
    {
        if (strpos($post->post_content, self::SHORTCODE_LOGIN_FORM)) {
            $postStates[] = __('Login and Register Page', AID_MICRO_ERP_THEME_TEXTDOMAIN);
        }
        return $postStates;
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