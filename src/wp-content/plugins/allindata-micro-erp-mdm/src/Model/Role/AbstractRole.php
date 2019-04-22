<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Role;

use WP_Role;

/**
 * Class AbstractRole
 * @package AllInData\MicroErp\Mdm\Model\Role
 */
abstract class AbstractRole implements RoleInterface
{
    const ROLE_LEVEL = '';

    /**
     * @return void
     */
    public function installRole()
    {
        add_role($this->getRoleLevel(), $this->getRoleLabel(), $this->getCapabilities());
        do_action('role_install_'.$this->getRoleLevel(), $this->getRoleLevel());
    }

    /**
     * @return void
     */
    public function removeRole()
    {
        remove_role($this->getRoleLevel());
        do_action('role_remove_'.$this->getRoleLevel(), $this->getRoleLevel());
    }

    /**
     * @return string
     */
    abstract public function getRoleLabel(): string;

    /**
     * @return string
     */
    public function getRoleLevel(): string
    {
        return static::ROLE_LEVEL;
    }

    /**
     * @return WP_Role|null
     */
    public function getRoleInstance(): ?WP_Role
    {
        return get_role($this->getRoleLevel());
    }

    /**
     * @return array
     */
    public function getCapabilities(): array
    {
        $role = get_role($this->getRoleLevel());
        if (!$role) {
            return [];
        }
        return $role->capabilities;
    }
}