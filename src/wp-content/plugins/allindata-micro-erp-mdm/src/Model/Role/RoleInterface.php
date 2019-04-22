<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Role;

use WP_Role;

/**
 * Interface RoleInterface
 * @package AllInData\MicroErp\Mdm\Model\Role
 */
interface RoleInterface
{
    /**
     * @return void
     */
    public function installRole();

    /**
     * @return void
     */
    public function removeRole();

    /**
     * @return string
     */
    public function getRoleLabel(): string;

    /**
     * @return string
     */
    public function getRoleLevel(): string;

    /**
     * @return WP_Role|null
     */
    public function getRoleInstance(): ?WP_Role;

    /**
     * @return array
     */
    public function getCapabilities(): array;
}