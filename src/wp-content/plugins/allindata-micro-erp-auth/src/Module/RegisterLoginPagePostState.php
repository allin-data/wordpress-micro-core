<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Module;

use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Auth\Widget\Elementor\LoginForm;
use WP_Post;

/**
 * Class RegisterLoginPagePostState
 * @package AllInData\MicroErp\Planning\Module
 */
class RegisterLoginPagePostState implements PluginModuleInterface
{
    /*
     * URI Path
     */
    const REQUEST_URI_PATH_LOGIN = 'login';
    const SHORTCODE_LOGIN_FORM = '[micro_erp_auth_login_form]';

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_action('display_post_states', [$this, 'addLoginPageState'], 10, 2);
    }

    /**
     * @param array $postStates
     * @param WP_Post $post
     * @return array
     */
    public function addLoginPageState($postStates, $post)
    {
        if (strpos($post->post_content, self::SHORTCODE_LOGIN_FORM)) {
            $postStates[] = __('Login and Register Page', AID_MICRO_ERP_THEME_TEXTDOMAIN);
            return $postStates;
        }

        $data = get_post_meta($post->ID, '_elementor_data', true);
        if (!$data) {
            return $postStates;
        }

        if (strpos($data, LoginForm::WIDGET_NAME)) {
            $postStates[] = __('Login and Register Page', AID_MICRO_ERP_THEME_TEXTDOMAIN);
        }

        return $postStates;
    }

}