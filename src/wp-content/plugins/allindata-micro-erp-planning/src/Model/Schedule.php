<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Model;

use AllInData\MicroErp\Core\Model\AbstractModel;

/**
 * Class Schedule
 * @package AllInData\MicroErp\Planning\Model
 */
class Schedule extends AbstractModel
{
    /**
     * @var int|string|null
     */
    private $id;
    /**
     * @var int|null
     */
    private $calendarId;
    /**
     * @var string|null
     */
    private $title;
    /**
     * @var string|null
     */
    private $state;
    /**
     * @var string|null
     */
    private $category;
    /**
     * @var string|null
     */
    private $location;
    /**
     * @var string|null
     */
    private $dueDateClass;
    /**
     * @var string|null
     */
    private $start;
    /**
     * @var string|null
     */
    private $end;
    /**
     * @var bool|int|null
     */
    private $isAllDay;
    /**
     * @var bool|int|null
     */
    private $isReadOnly;

    /**
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string|null $id
     * @return Schedule
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCalendarId(): ?int
    {
        return $this->calendarId;
    }

    /**
     * @param int|null $calendarId
     * @return Schedule
     */
    public function setCalendarId(?int $calendarId): Schedule
    {
        $this->calendarId = $calendarId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     * @return Schedule
     */
    public function setTitle(?string $title): Schedule
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string|null $state
     * @return Schedule
     */
    public function setState(?string $state): Schedule
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCategory(): ?string
    {
        return $this->category;
    }

    /**
     * @param string|null $category
     * @return Schedule
     */
    public function setCategory(?string $category): Schedule
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * @param string|null $location
     * @return Schedule
     */
    public function setLocation(?string $location): Schedule
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDueDateClass(): ?string
    {
        return $this->dueDateClass;
    }

    /**
     * @param string|null $dueDateClass
     * @return Schedule
     */
    public function setDueDateClass(?string $dueDateClass): Schedule
    {
        $this->dueDateClass = $dueDateClass;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStart(): ?string
    {
        return $this->start;
    }

    /**
     * @param string|null $start
     * @return Schedule
     */
    public function setStart(?string $start): Schedule
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEnd(): ?string
    {
        return $this->end;
    }

    /**
     * @param string|null $end
     * @return Schedule
     */
    public function setEnd(?string $end): Schedule
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return bool|int|null
     */
    public function getIsAllDay()
    {
        return $this->isAllDay;
    }

    /**
     * @param bool|int|null $isAllDay
     * @return Schedule
     */
    public function setIsAllDay($isAllDay)
    {
        $this->isAllDay = $isAllDay;
        return $this;
    }

    /**
     * @return bool|int|null
     */
    public function getIsReadOnly()
    {
        return $this->isReadOnly;
    }

    /**
     * @param bool|int|null $isReadOnly
     * @return Schedule
     */
    public function setIsReadOnly($isReadOnly)
    {
        $this->isReadOnly = $isReadOnly;
        return $this;
    }
}