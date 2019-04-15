<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Module;

use AllInData\MicroErp\Auth\Helper\LoginPageStateHelper;
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
     * @var LoginPageStateHelper
     */
    private $loginPageStateHelper;

    /**
     * RegisterLoginPagePostState constructor.
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
        add_action('display_post_states', [$this, 'addLoginPageState'], 10, 2);
    }

    /**
     * @param array $postStates
     * @param WP_Post $post
     * @return array
     */
    public function addLoginPageState($postStates, $post)
    {
        if ($this->loginPageStateHelper->isLoginPageState($post)) {
            $postStates[] = $this->loginPageStateHelper->getLoginPageStateName();
        }

        return $postStates;
    }

}