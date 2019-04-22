<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Role;

/**
 * Class UserRole
 * @package AllInData\MicroErp\Mdm\Model\Role
 */
class UserRole extends AbstractRole
{
    const ROLE_LEVEL = 'micro_erp_acl_level_user';

    /**
     * @inheritDoc
     */
    public function getRoleLabel(): string
    {
        return __('Micro ERP - User', AID_MICRO_ERP_MDM_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function removeRole()
    {
        // @deprecated role names
        remove_role('micro_erp_acl_level_user_default');
        remove_role('dgr_acl_level_user_default');
        parent::removeRole();
    }
}