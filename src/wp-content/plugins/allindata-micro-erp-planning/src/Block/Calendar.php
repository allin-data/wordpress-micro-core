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
            $startDate = new DateTime($schedule->getStart());
            $endDate = new DateTime($schedule->getEnd());

            $scheduleSet[] = [
                'id' => $schedule->getId(),
                'calendarId' => $schedule->getCalendarId(),
                'title' => $schedule->getTitle(),
                'state' => $schedule->getState(),
                'category' => $schedule->getCategory(),
                'location' => $schedule->getLocation(),
                'dueDateClass' => $schedule->getDueDateClass(),
                'start' => $startDate->format('Y-m-d\TH:i:s+09:00'),
                'end' => $endDate->format('Y-m-d\TH:i:s+09:00'),
                'isAllDay' => !!$schedule->getIsAllDay(),
                'isReadOnly' => !!$schedule->getIsReadOnly()
            ];
        }

//        $scheduleSet[] =
//            [
//                'id' => 1,
//                'calendarId' => 1,
//                'title' => 'Demo Schedule #01',
//                'category' => 'time',
//                'dueDateClass' => '',
//                'start' => '2019-04-18T22:30:00+09:00',
//                'end' => '2019-04-19T02:30:00+09:00',
//                'isReadOnly' => false
//            ];

//        [
//            'id' => 1,
//            'calendarId' => 1,
//            'title' => 'Demo Schedule #01',
//            'category' => 'time',
//            'dueDateClass' => '',
//            'start' => '2019-04-18T22:30:00+09:00',
//            'end' => '2019-04-19T02:30:00+09:00',
//            'isReadOnly' => false
//        ],
        //'start' => '2019-04-18T22:30:00+09:00',
        //'end' => '2019-04-19T02:30:00+09:00',
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