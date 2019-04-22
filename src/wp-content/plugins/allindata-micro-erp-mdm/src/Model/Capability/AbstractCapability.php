<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Capability;

/**
 * Class AbstractCapability
 * @package AllInData\MicroErp\Mdm\Model\Capability
 */
abstract class AbstractCapability implements CapabilityInterface
{
    const CAPABILITY = '';

    /**
     * @var string[]
     */
    private $roles;

    /**
     * AbstractCapability constructor.
     * @param string[] $roles
     */
    public function __construct(array $roles = [])
    {
        $this->roles = $roles;
        foreach ($roles as $role) {
            add_action('role_install_'.$role, [$this, 'installCapability']);
            add_action('role_remove_'.$role, [$this, 'removeCapability']);
        }
    }

    /**
     * @inheritDoc
     */
    public function install()
    {
        foreach ($this->roles as $role) {
            $this->installCapability($role);
        }
    }

    /**
     * @inheritDoc
     */
    public function remove()
    {
        foreach ($this->roles as $role) {
            $this->removeCapability($role);
        }
    }

    /**
     * @param string $roleName
     */
    public function installCapability(string $roleName)
    {
        $role = get_role($roleName);
        if ($role && !$role->has_cap($this->getCapabilityName())) {
            $role->add_cap($this->getCapabilityName());
        }
    }

    /**
     * @param string $roleName
     */
    public function removeCapability(string $roleName)
    {
        $role = get_role($roleName);
        if ($role && $role->has_cap($this->getCapabilityName())) {
            $role->remove_cap($this->getCapabilityName());
        }
    }

    /**
     * @return string
     */
    public function getCapabilityName(): string
    {
        return static::CAPABILITY;
    }
}