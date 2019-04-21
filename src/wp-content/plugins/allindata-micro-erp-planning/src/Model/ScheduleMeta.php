<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Model;

use AllInData\MicroErp\Core\Model\AbstractModel;

/**
 * Class ScheduleMeta
 * @package AllInData\MicroErp\Planning\Model
 */
class ScheduleMeta extends AbstractModel
{
    /**
     * @var string|null
     */
    private $memo;
    /**
     * @var string|null
     */
    private $hasToOrCc;
    /**
     * @var string|null
     */
    private $hasRecurrenceRule;
    /**
     * @var string|null
     */
    private $class;
    /**
     * @var ScheduleMetaCreator|null
     */
    private $creator;

    /**
     * @return string|null
     */
    public function getMemo(): ?string
    {
        return $this->memo;
    }

    /**
     * @param string|null $memo
     * @return ScheduleMeta
     */
    public function setMemo(?string $memo): ScheduleMeta
    {
        $this->memo = $memo;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHasToOrCc(): ?string
    {
        return $this->hasToOrCc;
    }

    /**
     * @param string|null $hasToOrCc
     * @return ScheduleMeta
     */
    public function setHasToOrCc(?string $hasToOrCc): ScheduleMeta
    {
        $this->hasToOrCc = $hasToOrCc;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHasRecurrenceRule(): ?string
    {
        return $this->hasRecurrenceRule;
    }

    /**
     * @param string|null $hasRecurrenceRule
     * @return ScheduleMeta
     */
    public function setHasRecurrenceRule(?string $hasRecurrenceRule): ScheduleMeta
    {
        $this->hasRecurrenceRule = $hasRecurrenceRule;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string|null $class
     * @return ScheduleMeta
     */
    public function setClass(?string $class): ScheduleMeta
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return ScheduleMetaCreator|null
     */
    public function getCreator(): ?ScheduleMetaCreator
    {
        return $this->creator;
    }

    /**
     * @param ScheduleMetaCreator|null $creator
     * @return ScheduleMeta
     */
    public function setCreator(?ScheduleMetaCreator $creator): ScheduleMeta
    {
        $this->creator = $creator;
        return $this;
    }
}