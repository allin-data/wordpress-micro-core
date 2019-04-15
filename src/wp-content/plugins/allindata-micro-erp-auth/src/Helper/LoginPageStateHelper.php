<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Helper;

use AllInData\MicroErp\Auth\Widget\Elementor\LoginForm;
use WP_Post;
use WP_Query;

/**
 * Class LoginPageStateHelper
 * @package AllInData\MicroErp\Auth\Helper
 */
class LoginPageStateHelper
{
    /*
     * URI Path
     */
    const SHORTCODE_LOGIN_FORM = '[micro_erp_auth_login_form]';

    /**
     * @return string
     */
    public function getLoginPageStateName()
    {
        return __('Login and Register Page', AID_MICRO_ERP_THEME_TEXTDOMAIN);
    }

    /**
     * @param WP_Post $post
     * @return bool
     */
    public function isLoginPageState(WP_Post $post)
    {
        if (strpos($post->post_content, self::SHORTCODE_LOGIN_FORM)) {
            return true;
        }

        $data = get_post_meta($post->ID, '_elementor_data', true);
        if (!$data) {
            return false;
        }

        if (strpos($data, LoginForm::WIDGET_NAME)) {
            return true;
        }

        return false;
    }

    /**
     * @return WP_Post|null
     */
    public function getLoginPagePost()
    {
        $query = new WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish'
        ]);

        /** @var WP_Post[] $postSet */
        $postSet = $query->get_posts();
        foreach ($postSet as $post) {
            if ($this->isLoginPageState($post)) {
                return $post;
            }
        }

        return null;
    }
}