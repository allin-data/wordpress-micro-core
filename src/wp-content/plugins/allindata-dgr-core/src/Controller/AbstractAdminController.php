<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\Controller;

use AllInData\Dgr\Cms\Model\UserRole;

/**
 * Class AbstractAdminController
 * @package AllInData\Dgr\Core\Controller
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