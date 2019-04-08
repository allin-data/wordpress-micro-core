<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Controller;

use AllInData\MicroErp\Mdm\Model\UserRole;

/**
 * Class AbstractAdminController
 * @package AllInData\MicroErp\Core\Controller
 */
abstract class AbstractAdminController extends AbstractController implements PluginControllerInterface
{
    /**
     * @inheritDoc
     */
    protected function isAllowed(): bool
    {
        if (!is_user_logged_in() || !current_user_can(UserRole::ROLE_LEVEL_ADMINISTRATION)) {
            return false;
        }
        return true;
    }
}