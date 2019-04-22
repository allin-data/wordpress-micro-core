<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Model\Capability;

use AllInData\MicroErp\Mdm\Model\Capability\AbstractCapability;

/**
 * Class DeleteSchedule
 * @package AllInData\MicroErp\Planning\Model\Capability
 */
class DeleteSchedule extends AbstractCapability
{
    const CAPABILITY = 'micro_erp_planning_schedule_delete';
}