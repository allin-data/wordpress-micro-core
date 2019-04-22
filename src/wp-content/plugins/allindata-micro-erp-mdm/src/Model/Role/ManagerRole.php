<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Role;

/**
 * Class ManagerRole
 * @package AllInData\MicroErp\Mdm\Model\Role
 */
class ManagerRole extends AbstractRole
{
    const ROLE_LEVEL = 'micro_erp_acl_level_manager';

    /**
     * @inheritDoc
     */
    public function getRoleLabel(): string
    {
        return __('Micro ERP - Manager', AID_MICRO_ERP_MDM_TEXTDOMAIN);
    }
}