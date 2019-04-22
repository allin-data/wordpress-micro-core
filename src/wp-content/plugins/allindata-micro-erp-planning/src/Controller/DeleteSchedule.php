<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Controller;

use AllInData\MicroErp\Core\Controller\AbstractController;
use AllInData\MicroErp\Planning\Model\Resource\Schedule as ScheduleResource;
use AllInData\MicroErp\Planning\Model\Schedule;
use DateTime;
use RuntimeException;

/**
 * Class DeleteSchedule
 * @package AllInData\MicroErp\Planning\Controller
 */
class DeleteSchedule extends AbstractController
{
    const ACTION_SLUG = 'micro_erp_planning_delete_schedule';

    /**
     * @var ScheduleResource
     */
    private $scheduleResource;

    /**
     * DeleteSchedule constructor.
     * @param ScheduleResource $scheduleResource
     */
    public function __construct(ScheduleResource $scheduleResource)
    {
        $this->scheduleResource = $scheduleResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $scheduleId = $this->getParam('scheduleId');
        /** @var Schedule $entity */
        $entity = $this->scheduleResource->loadById($scheduleId);
        if (!$entity->getId()) {
            throw new RuntimeException('Could not delete schedule');
        }
        return $this->scheduleResource->deleteById($entity->getId());
    }

    /**
     * @inheritDoc
     */
    protected function getRequiredCapabilitySet(): array
    {
        return [\AllInData\MicroErp\Planning\Model\Capability\DeleteSchedule::CAPABILITY];
    }
}