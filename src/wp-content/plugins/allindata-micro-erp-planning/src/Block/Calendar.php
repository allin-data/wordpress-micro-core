<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Planning\Controller\CreateSchedule;
use AllInData\MicroErp\Planning\Controller\DeleteSchedule;
use AllInData\MicroErp\Planning\Controller\UpdateSchedule;
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
     * @param string $key
     * @return string|null
     */
    public function getCommonStyle($key)
    {
        return $this->getAttribute('advanced-style_common.'.$key);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getCommonBorder($key)
    {
        $size = $this->getAttribute('advanced-style_common.'.$key.'.px');
        $style = $this->getAttribute('advanced-style_common.'.$key.'.style');
        $color = $this->getAttribute('advanced-style_common.'.$key.'.color');
        return $size.'px '.$style.' '.$color;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthStyle($key)
    {
        return $this->getAttribute('advanced-style_month.'.$key);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthBorder($key)
    {
        $size = $this->getAttribute('advanced-style_month.'.$key.'.px');
        $style = $this->getAttribute('advanced-style_month.'.$key.'.style');
        $color = $this->getAttribute('advanced-style_month.'.$key.'.color');
        return $size.'px '.$style.' '.$color;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthBoxShadow($key)
    {
        $sizeTop = $this->getAttribute('advanced-style_month.'.$key.'.px.top');
        $sizeRight = $this->getAttribute('advanced-style_month.'.$key.'.px.right');
        $sizeBottom = $this->getAttribute('advanced-style_month.'.$key.'.px.bottom');
        $sizeLeft = $this->getAttribute('advanced-style_month.'.$key.'.px.left');
        $color = $this->getAttribute('advanced-style_month.'.$key.'.color');
        return $sizeTop.'px '.$sizeRight.'px '.$sizeBottom.'px '.$sizeLeft.'px '.$color;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthPadding($key)
    {
        $sizeTop = $this->getAttribute('advanced-style_month.'.$key.'.px.top');
        $sizeRight = $this->getAttribute('advanced-style_month.'.$key.'.px.right');
        $sizeBottom = $this->getAttribute('advanced-style_month.'.$key.'.px.bottom');
        $sizeLeft = $this->getAttribute('advanced-style_month.'.$key.'.px.left');
        return $sizeTop.'px '.$sizeRight.'px '.$sizeBottom.'px '.$sizeLeft.'px';
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getWeekStyle($key)
    {
        return $this->getAttribute('advanced-style_week.'.$key);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getWeekBorder($key)
    {
        $size = $this->getAttribute('advanced-style_week.'.$key.'.px');
        $style = $this->getAttribute('advanced-style_week.'.$key.'.style');
        $color = $this->getAttribute('advanced-style_week.'.$key.'.color');
        return $size.'px '.$style.' '.$color;
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

    /**
     * @return string
     */
    public function getUpdateScheduleActionSlug()
    {
        return UpdateSchedule::ACTION_SLUG;
    }

    /**
     * @return string
     */
    public function getDeleteScheduleActionSlug()
    {
        return DeleteSchedule::ACTION_SLUG;
    }
}