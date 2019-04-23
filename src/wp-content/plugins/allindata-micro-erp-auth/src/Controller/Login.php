<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Controller;

use AllInData\MicroErp\Core\Controller\AbstractAnonController;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use WP_User;

/**
 * Class Login
 * @package AllInData\MicroErp\Auth\Controller
 */
class Login extends AbstractAnonController implements PluginControllerInterface
{
    const ACTION_SLUG = 'login';

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $doRemeber = $this->getParam('rememberme') === 'on' ? true : false;
        $credentials['user_login'] = $this->getParam('username');
        $credentials['user_password'] = $this->getParam('password');
        $credentials['remember'] = $doRemeber;
        $user = wp_signon($credentials, is_ssl());
        if (is_wp_error($user)) {
            /** @var \WP_Error $user */
            $this->throwErrorMessage($user->get_error_message());
        }
        wp_set_auth_cookie($user->ID, $doRemeber, is_ssl());
    }
}