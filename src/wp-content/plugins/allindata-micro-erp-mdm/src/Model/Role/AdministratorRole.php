<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Role;

/**
 * Class AdministratorRole
 * @package AllInData\MicroErp\Mdm\Model\Role
 */
class AdministratorRole extends AbstractRole
{
    const ROLE_LEVEL = 'administrator';

    /**
     * @inheritDoc
     */
    public function installRole()
    {
        // already exists
    }

    /**
     * @inheritDoc
     */
    public function getRoleLabel(): string
    {
        return __('Administrator', AID_MICRO_ERP_MDM_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function getCapabilities(): array
    {
        return ['administrator'];
    }
}