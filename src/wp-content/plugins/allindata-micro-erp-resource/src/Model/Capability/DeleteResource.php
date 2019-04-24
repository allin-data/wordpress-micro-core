<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Capability;

use AllInData\MicroErp\Mdm\Model\Capability\AbstractCapability;

/**
 * Class DeleteResource
 * @package AllInData\MicroErp\Resource\Model\Capability
 */
class DeleteResource extends AbstractCapability
{
    const CAPABILITY = 'micro_erp_resource_resource_delete';
}