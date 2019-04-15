<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Controller;

use AllInData\MicroErp\Core\Controller\AbstractAnonController;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;

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
        $credentials['user_login'] = $this->getParam('username');
        $credentials['user_password'] = $this->getParam('password');
        $credentials['remember'] = true;
        wp_signon($credentials, false);
    }
}