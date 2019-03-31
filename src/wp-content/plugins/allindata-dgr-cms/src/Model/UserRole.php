<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model;

use AllInData\Dgr\Core\Model\AbstractModel;

/**
 * Class User
 * @package AllInData\Dgr\Cms\Model
 */
class UserRole extends AbstractModel
{
    const ROLE_LEVEL_USER_DEFAULT = 'dgr_acl_level_user_default';
    const ROLE_LEVEL_ADMINISTRATION = 'administrator';
}