<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Report\Helper;

use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Planning\Model\Collection\Schedule as ScheduleCollection;
use AllInData\MicroErp\Planning\Model\Schedule;
use DateTime;
use Exception;

/**
 * Class UtilizationReportHelper
 * @package AllInData\MicroErp\Report\Helper
 */
class UtilizationReportHelper
{
    /**
     * @var ScheduleCollection
     */
    private $scheduleCollection;

    /**
     * UtilizationReportHelper constructor.
     * @param ScheduleCollection $scheduleCollection
     */
    public function __construct(ScheduleCollection $scheduleCollection)
    {
        $this->scheduleCollection = $scheduleCollection;
    }

    /**
     * @param int $userId
     * @param int $dailyWorkingHours
     * @param DateTime $dateStart
     * @param DateTime $dateEnd
     * @return float
     * @throws Exception
     */
    public function getUtilizationFactorForUserInDateTimeRange(
        int $userId,
        int $dailyWorkingHours,
        DateTime $dateStart,
        DateTime $dateEnd
    ): float {
        $scheduleSet = $this->getSchedulesInDateTimeRange($userId, $dateStart, $dateEnd);

        //@TODO respect holidays and so forth, with Yasumi\Yasumi
        $amountOfDaysInMonth = $this->getWeekdays((int)$dateStart->format('m'), (int)$dateStart->format('Y'));
        $amountOfPlannedHours = $this->getAmountOfPlannedHours($scheduleSet, $dailyWorkingHours);

        if (0 >= $dailyWorkingHours || 0 >= $amountOfDaysInMonth) {
            $amountOfDaysInMonth = 0;
        }

        if (0 >= $amountOfPlannedHours) {
            return 0;
        }

        return ($amountOfPlannedHours / ($amountOfDaysInMonth * $dailyWorkingHours));
    }

    /**
     * @param Schedule[] $schedules
     * @param int $dailyWorkingHours
     * @return float
     * @throws Exception
     */
    private function getAmountOfPlannedHours(array $schedules, int $dailyWorkingHours): float
    {
        $duration = 0.0;
        foreach ($schedules as $schedule) {
            $startDate = new DateTime($schedule->getStart());
            $endDate = new DateTime($schedule->getEnd());
            $diffInterval = $endDate->diff($startDate, true);
            $duration += $diffInterval->d * $dailyWorkingHours;
            $duration += $diffInterval->h;
            if (0 < $diffInterval->m) {
                $duration += ($diffInterval->m / 60);
            }
        }

        return $duration;
    }

    /**
     * @param int $userId
     * @param DateTime $dateStart
     * @param DateTime $dateEnd
     * @return Schedule[]
     */
    private function getSchedulesInDateTimeRange(
        int $userId,
        DateTime $dateStart,
        DateTime $dateEnd
    ): array {
        $scheduleSet = $this->scheduleCollection->load(
            GenericCollection::NO_LIMIT,
            0,
            [
                'author' => $userId,
                'meta_query' => [
                    [
                        'key' => 'start',
                        'value' => $dateStart->format('Y-m-d 00:00:00'),
                        'compare' => '>=',
                    ],
                    [
                        'key' => 'end',
                        'value' => $dateEnd->format('Y-m-d 23:59:59'),
                        'compare' => '<=',
                    ]
                ]
            ]
        );

        return $scheduleSet;
    }

    /**
     * @param int $month
     * @param int $year
     * @return int
     */
    private function getWeekdays(int $month, int $year): int
    {
        $lastDay = date("t", mktime(0, 0, 0, $month, 1, $year));
        $weekdays = 0;
        for ($d = 29; $d <= $lastDay; $d++) {
            $wd = date("w", mktime(0, 0, 0, $month, $d, $year));
            if ($wd > 0 && $wd < 6) {
                $weekdays++;
            }
        }
        return $weekdays + 20;
    }
}