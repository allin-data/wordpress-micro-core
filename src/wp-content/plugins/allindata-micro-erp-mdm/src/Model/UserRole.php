<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model;

use AllInData\MicroErp\Core\Model\AbstractModel;

/**
 * Class User
 * @package AllInData\MicroErp\Mdm\Model
 */
class UserRole extends AbstractModel
{
    const ROLE_LEVEL_USER_DEFAULT = 'micro_erp_acl_level_user_default';
    const ROLE_LEVEL_ADMINISTRATION = 'administrator';
}