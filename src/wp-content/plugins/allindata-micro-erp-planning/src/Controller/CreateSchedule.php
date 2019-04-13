<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Controller;

use AllInData\MicroErp\Core\Controller\AbstractController;
use AllInData\MicroErp\Planning\Model\Schedule;
use AllInData\MicroErp\Planning\Model\Validator\Schedule as ScheduleValidator;
use AllInData\MicroErp\Planning\Model\Resource\Schedule as ScheduleResource;

/**
 * Class CreateSchedule
 * @package AllInData\MicroErp\Planning\Controller
 */
class CreateSchedule extends AbstractController
{
    const ACTION_SLUG = 'micro_erp_planning_create_schedule';

    /**
     * @var ScheduleValidator
     */
    private $scheduleValidator;
    /**
     * @var ScheduleResource
     */
    private $scheduleResource;

    /**
     * CreateSchedule constructor.
     * @param ScheduleValidator $scheduleValidator
     * @param ScheduleResource $scheduleResource
     */
    public function __construct(ScheduleValidator $scheduleValidator, ScheduleResource $scheduleResource)
    {
        $this->scheduleValidator = $scheduleValidator;
        $this->scheduleResource = $scheduleResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $calendarId = (int)$this->getParam('calendarId');
        $title = $this->getParam('title');
        $state = $this->getParam('state');
        $category = $this->getParam('category');
        $location = $this->getParam('location');
        $dueDateClass = $this->getParam('dueDateClass');
        $start = $this->getParam('start');
        $end = $this->getParam('end');
        $isAllDay = $this->getParam('isAllDay');
        $isReadOnly = $this->getParam('isReadOnly');

        /** @var Schedule $schedule */
        $schedule = $this->scheduleResource->getModelFactory()->create();
        $schedule
            ->setCalendarId($calendarId)
            ->setTitle($title)
            ->setState($state)
            ->setCategory($category)
            ->setLocation($location)
            ->setDueDateClass($dueDateClass)
            ->setStart($start)
            ->setEnd($end)
            ->setIsAllDay($isAllDay)
            ->setIsReadOnly($isReadOnly);
        if (!$this->scheduleValidator->validate($schedule)->isValid()) {
            $this->throwErrorMessage(implode(',', $this->scheduleValidator->getErrors()));
        }
        $this->scheduleResource->save($schedule);
    }
}