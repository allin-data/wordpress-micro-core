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
     * @var int|string|null
     */
    private $calendarId;
    /**
     * @var string|null
     */
    private $title;
    /**
     * @var string|null
     */
    private $body;
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
    private $isAllday;
    /**
     * @var bool|int|null
     */
    private $isFocused;
    /**
     * @var bool|int|null
     */
    private $isPending;
    /**
     * @var bool|int|null
     */
    private $isReadOnly;
    /**
     * @var bool|int|null
     */
    private $isVisible;
    /**
     * @var int|null
     */
    private $goingDuration;
    /**
     * @var int|null
     */
    private $comingDuration;
    /**
     * @var string|null
     */
    private $recurrenceRule;
    /**
     * @var string|null
     */
    private $color;
    /**
     * @var string|null
     */
    private $bgColor;
    /**
     * @var string|null
     */
    private $dragBgColor;
    /**
     * @var string|null
     */
    private $borderColor;
    /**
     * @var string|null
     */
    private $customStyle;
    /**
     * @var ScheduleMeta|null
     */
    private $raw;

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
     * @return int|string|null
     */
    public function getCalendarId()
    {
        return $this->calendarId;
    }

    /**
     * @param int|string|null $calendarId
     * @return Schedule
     */
    public function setCalendarId($calendarId)
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
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * @param string|null $body
     * @return Schedule
     */
    public function setBody(?string $body): Schedule
    {
        $this->body = $body;
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
    public function getIsAllday()
    {
        return $this->isAllday;
    }

    /**
     * @param bool|int|null $isAllday
     * @return Schedule
     */
    public function setIsAllday($isAllday)
    {
        $this->isAllday = $isAllday;
        return $this;
    }

    /**
     * @return bool|int|null
     */
    public function getIsFocused()
    {
        return $this->isFocused;
    }

    /**
     * @param bool|int|null $isFocused
     * @return Schedule
     */
    public function setIsFocused($isFocused)
    {
        $this->isFocused = $isFocused;
        return $this;
    }

    /**
     * @return bool|int|null
     */
    public function getIsPending()
    {
        return $this->isPending;
    }

    /**
     * @param bool|int|null $isPending
     * @return Schedule
     */
    public function setIsPending($isPending)
    {
        $this->isPending = $isPending;
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

    /**
     * @return bool|int|null
     */
    public function getIsVisible()
    {
        return $this->isVisible;
    }

    /**
     * @param bool|int|null $isVisible
     * @return Schedule
     */
    public function setIsVisible($isVisible)
    {
        $this->isVisible = $isVisible;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getGoingDuration(): ?int
    {
        return $this->goingDuration;
    }

    /**
     * @param int|null $goingDuration
     * @return Schedule
     */
    public function setGoingDuration(?int $goingDuration): Schedule
    {
        $this->goingDuration = $goingDuration;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getComingDuration(): ?int
    {
        return $this->comingDuration;
    }

    /**
     * @param int|null $comingDuration
     * @return Schedule
     */
    public function setComingDuration(?int $comingDuration): Schedule
    {
        $this->comingDuration = $comingDuration;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecurrenceRule(): ?string
    {
        return $this->recurrenceRule;
    }

    /**
     * @param string|null $recurrenceRule
     * @return Schedule
     */
    public function setRecurrenceRule(?string $recurrenceRule): Schedule
    {
        $this->recurrenceRule = $recurrenceRule;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     * @return Schedule
     */
    public function setColor(?string $color): Schedule
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBgColor(): ?string
    {
        return $this->bgColor;
    }

    /**
     * @param string|null $bgColor
     * @return Schedule
     */
    public function setBgColor(?string $bgColor): Schedule
    {
        $this->bgColor = $bgColor;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDragBgColor(): ?string
    {
        return $this->dragBgColor;
    }

    /**
     * @param string|null $dragBgColor
     * @return Schedule
     */
    public function setDragBgColor(?string $dragBgColor): Schedule
    {
        $this->dragBgColor = $dragBgColor;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    /**
     * @param string|null $borderColor
     * @return Schedule
     */
    public function setBorderColor(?string $borderColor): Schedule
    {
        $this->borderColor = $borderColor;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCustomStyle(): ?string
    {
        return $this->customStyle;
    }

    /**
     * @param string|null $customStyle
     * @return Schedule
     */
    public function setCustomStyle(?string $customStyle): Schedule
    {
        $this->customStyle = $customStyle;
        return $this;
    }

    /**
     * @return ScheduleMeta|null
     */
    public function getRaw(): ?ScheduleMeta
    {
        return $this->raw;
    }

    /**
     * @param ScheduleMeta|null $raw
     * @return Schedule
     */
    public function setRaw(?ScheduleMeta $raw): Schedule
    {
        $this->raw = $raw;
        return $this;
    }
}