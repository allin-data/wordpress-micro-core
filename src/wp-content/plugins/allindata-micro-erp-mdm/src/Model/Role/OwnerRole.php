<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Role;

/**
 * Class OwnerRole
 * @package AllInData\MicroErp\Mdm\Model\Role
 */
class OwnerRole extends AbstractRole
{
    const ROLE_LEVEL = 'micro_erp_acl_level_owner';

    /**
     * @inheritDoc
     */
    public function getRoleLabel(): string
    {
        return __('Micro ERP - Owner', AID_MICRO_ERP_MDM_TEXTDOMAIN);
    }
}