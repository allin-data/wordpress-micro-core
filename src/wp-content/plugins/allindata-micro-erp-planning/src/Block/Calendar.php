<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Planning\Controller\CreateSchedule;
use AllInData\MicroErp\Planning\Controller\DeleteSchedule;
use AllInData\MicroErp\Planning\Controller\UpdateSchedule;
use AllInData\MicroErp\Planning\Model\Collection\Schedule as ScheduleCollection;
use AllInData\MicroErp\Planning\Model\Schedule;
use AllInData\MicroErp\Resource\Model\Resource;
use AllInData\MicroErp\Resource\Model\ResourceType;

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
     * @var GenericCollection
     */
    private $resourceTypeCollection;
    /**
     * @var GenericCollection
     */
    private $resourceCollection;

    /**
     * Calendar constructor.
     * @param ScheduleCollection $scheduleCollection
     * @param GenericCollection $resourceTypeCollection
     * @param GenericCollection $resourceCollection
     */
    public function __construct(
        ScheduleCollection $scheduleCollection,
        GenericCollection $resourceTypeCollection,
        GenericCollection $resourceCollection
    ) {
        $this->scheduleCollection = $scheduleCollection;
        $this->resourceTypeCollection = $resourceTypeCollection;
        $this->resourceCollection = $resourceCollection;
    }

    /**
     * @return array[][] 'meta' with resource type information, 'items' with resource information
     */
    public function getResourceMap(): array
    {
        $resourceTypes = $this->resourceTypeCollection->loadBypassOwnership();
        $resourcesMeta = [];
        $resourcesItemSet = [];
        foreach ($resourceTypes as $resourceType) {
            /** @var ResourceType $resourceType */
            if ($resourceType->getIsDisabled()) {
                continue;
            }

            $resourceSet = $this->resourceCollection->load([
                'meta_query' => [
                    [
                        'key' => 'type_id',
                        'value' => $resourceType->getId(),
                        'compare' => '=',
                    ],
                ]
            ]);

            $resourcesMeta[$resourceType->getId()] = $resourceType;
            $resourcesItemSet[$resourceType->getId()] = $resourceSet;
        }

        return [
            'meta' => $resourcesMeta,
            'items' => $resourcesItemSet
        ];
    }

    /**
     * @return ResourceType[]
     */
    public function getResourceTypes(): array
    {
        $resourceTypes = $this->resourceTypeCollection->loadBypassOwnership(
            GenericCollection::NO_LIMIT,
            0
        );
        $resourceTypeSet = [];
        foreach ($resourceTypes as $resourceType) {
            /** @var ResourceType $resourceType */
            if ($resourceType->getIsDisabled()) {
                continue;
            }
            $resourceTypeSet[] = $resourceType;
        }

        return $resourceTypeSet;
    }

    /**
     * @param ResourceType $resourceType
     * @return Resource[]
     */
    public function getResourcesByType(ResourceType $resourceType): array
    {
        $resourceSet = $this->resourceCollection->load(
            GenericCollection::NO_LIMIT,
            0,
            [
                'meta_query' => [
                    [
                        'key' => 'type_id',
                        'value' => $resourceType->getId(),
                        'compare' => '=',
                    ],
                ]
            ]
        );

        return $resourceSet;
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
        $key = $this->mapKey($key);
        return $this->getAttribute('advanced_style_common_' . $key);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getCommonBorder($key)
    {
        $key = $this->mapKey($key);
        $size = $this->getAttribute('advanced_style_common_' . $key . '.px');
        $style = $this->getAttribute('advanced_style_common_' . $key . '.style');
        $color = $this->getAttribute('advanced_style_common_' . $key . '.color');
        return $size . 'px ' . $style . ' ' . $color;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthStyle($key)
    {
        $key = $this->mapKey($key);
        return $this->getAttribute('advanced_style_month_' . $key);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthBorder($key)
    {
        $key = $this->mapKey($key);
        $size = $this->getAttribute('advanced_style_month_' . $key . '.px');
        $style = $this->getAttribute('advanced_style_month_' . $key . '.style');
        $color = $this->getAttribute('advanced_style_month_' . $key . '.color');
        return $size . 'px ' . $style . ' ' . $color;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthBoxShadow($key)
    {
        $key = $this->mapKey($key);
        $sizeTop = $this->getAttribute('advanced_style_month_' . $key . '.px.top');
        $sizeRight = $this->getAttribute('advanced_style_month_' . $key . '.px.right');
        $sizeBottom = $this->getAttribute('advanced_style_month_' . $key . '.px.bottom');
        $sizeLeft = $this->getAttribute('advanced_style_month_' . $key . '.px.left');
        $color = $this->getAttribute('advanced_style_month_' . $key . '.color');
        return $sizeTop . 'px ' . $sizeRight . 'px ' . $sizeBottom . 'px ' . $sizeLeft . 'px ' . $color;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthPadding($key)
    {
        $key = $this->mapKey($key);
        $sizeTop = $this->getAttribute('advanced_style_month_' . $key . '.px.top');
        $sizeRight = $this->getAttribute('advanced_style_month_' . $key . '.px.right');
        $sizeBottom = $this->getAttribute('advanced_style_month_' . $key . '.px.bottom');
        $sizeLeft = $this->getAttribute('advanced_style_month_' . $key . '.px.left');
        return $sizeTop . 'px ' . $sizeRight . 'px ' . $sizeBottom . 'px ' . $sizeLeft . 'px';
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getWeekStyle($key)
    {
        $key = $this->mapKey($key);
        return $this->getAttribute('advanced_style_week_' . $key);
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getWeekBorder($key)
    {
        $key = $this->mapKey($key);
        $size = $this->getAttribute('advanced_style_week_' . $key . '.px');
        $style = $this->getAttribute('advanced_style_week_' . $key . '.style');
        $color = $this->getAttribute('advanced_style_week_' . $key . '.color');
        return $size . 'px ' . $style . ' ' . $color;
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

    /**
     * @param string $key
     * @return string
     */
    private function mapKey($key)
    {
        return str_replace('.', '_', $key);
    }
}