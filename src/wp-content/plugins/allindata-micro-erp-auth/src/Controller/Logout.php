<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Controller;

use AllInData\MicroErp\Core\Controller\AbstractAnonController;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;

/**
 * Class Logout
 * @package AllInData\MicroErp\Auth\Controller
 */
class Logout extends AbstractAnonController implements PluginControllerInterface
{
    const ACTION_SLUG = 'micro_erp_auth_logout';

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        wp_logout();
    }
}