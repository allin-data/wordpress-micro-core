<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Planning\Controller\CreateSchedule;
use AllInData\MicroErp\Planning\Model\Collection\Schedule as ScheduleCollection;
use AllInData\MicroErp\Planning\Model\Schedule;
use DateTime;

/**
 * Class Calendar
 * @package AllInData\MicroErp\Planning\Block\Admin
 */
class Calendar extends AbstractBlock
{
    /**
     * @var ScheduleCollection
     */
    private $scheduleCollection;

    /**
     * Calendar constructor.
     * @param ScheduleCollection $scheduleCollection
     */
    public function __construct(ScheduleCollection $scheduleCollection)
    {
        $this->scheduleCollection = $scheduleCollection;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getSchedules()
    {

        $collection = $this->scheduleCollection->load();

        $scheduleSet = [];
        foreach ($collection as $schedule) {
            /** @var Schedule $schedule */
            $scheduleSet[] = $schedule->toArray();
        }

        return $scheduleSet;
    }

    /**
     * @return string
     */
    public function getCreateScheduleActionSlug()
    {
        return CreateSchedule::ACTION_SLUG;
    }
}