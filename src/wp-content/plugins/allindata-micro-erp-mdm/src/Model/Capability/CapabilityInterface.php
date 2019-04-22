<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Capability;

/**
 * Interface CapabilityInterface
 * @package AllInData\MicroErp\Mdm\Model\Capability
 */
interface CapabilityInterface
{
    /**
     * @return void
     */
    public function install();

    /**
     * @return void
     */
    public function remove();

    /**
     * @param string $roleName
     */
    public function installCapability(string $roleName);

    /**
     * @param string $roleName
     */
    public function removeCapability(string $roleName);

    /**
     * @return string
     */
    public function getCapabilityName(): string;
}