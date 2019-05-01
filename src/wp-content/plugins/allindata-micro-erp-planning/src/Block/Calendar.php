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
use AllInData\MicroErp\Resource\Model\ResourceAttributeValue;
use AllInData\MicroErp\Resource\Model\ResourceType;
use AllInData\MicroErp\Resource\Model\ResourceTypeAttribute;

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
     * @var \AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute
     */
    private $attributeCollection;
    /**
     * @var GenericCollection
     */
    private $resourceAttributeValueCollection;
    /**
     * @var ResourceType[]
     */
    private $resourceTypeSet;

    /**
     * Calendar constructor.
     * @param ScheduleCollection $scheduleCollection
     * @param GenericCollection $resourceTypeCollection
     * @param GenericCollection $resourceCollection
     * @param \AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute $attributeCollection
     * @param GenericCollection $resourceAttributeValueCollection
     */
    public function __construct(
        ScheduleCollection $scheduleCollection,
        GenericCollection $resourceTypeCollection,
        GenericCollection $resourceCollection,
        \AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute $attributeCollection,
        GenericCollection $resourceAttributeValueCollection
    ) {
        $this->scheduleCollection = $scheduleCollection;
        $this->resourceTypeCollection = $resourceTypeCollection;
        $this->resourceCollection = $resourceCollection;
        $this->attributeCollection = $attributeCollection;
        $this->resourceAttributeValueCollection = $resourceAttributeValueCollection;
    }

    /**
     * @return string
     */
    public function getCalendarId(): string
    {
        return (string)$this->getAttribute('calendar_id');
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
        if (!$this->resourceTypeSet) {
            $this->resourceTypeSet = $this->resourceTypeCollection->loadBypassOwnership(
                GenericCollection::NO_LIMIT,
                0
            );
        }

        $resourceTypeSet = [];
        foreach ($this->resourceTypeSet as $resourceType) {
            /** @var ResourceType $resourceType */
            if ($resourceType->getIsDisabled()) {
                continue;
            }
            $resourceTypeSet[$resourceType->getId()] = $resourceType;
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
     * @param Resource $resource
     * @return string
     */
    public function getResourceName(Resource $resource): string
    {
        $unsortedResult = $this->attributeCollection->load(
            GenericCollection::NO_LIMIT,
            0,
            [
                'meta_query' => [
                    [
                        'key' => 'resource_type_id',
                        'value' => $resource->getTypeId(),
                        'compare' => '=',
                    ],
                    [
                        'key' => 'is_used_as_name',
                        'value' => (int)true,
                        'compare' => '=',
                    ],
                ]
            ]
        );
        $resourceTypeAttributes = [];
        foreach ($unsortedResult as $resourceTypeAttribute) {
            /** @var ResourceTypeAttribute $resourceTypeAttribute */
            $sortValue = $resourceTypeAttribute->getSortOrder();
            while (isset($this->resourceTypeAttributes[$sortValue])) {
                ++$sortValue;
            }
            $resourceTypeAttribute->setSortOrder($sortValue);
            $resourceTypeAttributes[$sortValue] = $resourceTypeAttribute;
        }
        ksort($resourceTypeAttributes, SORT_NUMERIC);

        $nameValueSet = [];
        foreach ($resourceTypeAttributes as $resourceTypeAttribute) {
            $values = $this->resourceAttributeValueCollection->load(
                GenericCollection::NO_LIMIT,
                0,
                [
                    'meta_query' => [
                        [
                            'key' => 'resource_attribute_id',
                            'value' => $resourceTypeAttribute->getId(),
                            'compare' => '=',
                        ],
                        [
                            'key' => 'resource_id',
                            'value' => $resource->getId(),
                            'compare' => '=',
                        ],
                    ]
                ]
            );

            /** @var ResourceAttributeValue $valueEntity */
            $valueEntity = array_shift($values);
            if (!$valueEntity) {
                continue;
            }
            $nameValueSet[] = $valueEntity->getValue();
        }

        // fallback entity name
        $entityName = __('Entity', AID_MICRO_ERP_PLANNING_TEXTDOMAIN);
        if (empty($nameValueSet)) {
            $resourceTypeSet = $this->getResourceTypes();
            if (isset($resourceTypeSet[$resource->getTypeId()])) {
                $entityName = $resourceTypeSet[$resource->getTypeId()]->getLabel();
            }
        } else {
            $entityName = implode(', ', $nameValueSet);
        }

        return sprintf(
            '%1$s (%2$s)',
            $entityName,
            $resource->getId()
        );
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
        $size = $this->getAttribute('advanced_style_common_' . $key . '_px');
        $style = $this->getAttribute('advanced_style_common_' . $key . '_style');
        $color = $this->getAttribute('advanced_style_common_' . $key . '_color');
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
        $size = $this->getAttribute('advanced_style_month_' . $key . '_px');
        $style = $this->getAttribute('advanced_style_month_' . $key . '_style');
        $color = $this->getAttribute('advanced_style_month_' . $key . '_color');
        return $size . 'px ' . $style . ' ' . $color;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthBoxShadow($key)
    {
        $key = $this->mapKey($key);
        $sizeTop = $this->getAttribute('advanced_style_month_' . $key . '_px_top');
        $sizeRight = $this->getAttribute('advanced_style_month_' . $key . '_px_right');
        $sizeBottom = $this->getAttribute('advanced_style_month_' . $key . '_px_bottom');
        $sizeLeft = $this->getAttribute('advanced_style_month_' . $key . '_px_left');
        $color = $this->getAttribute('advanced_style_month_' . $key . '_color');
        return $sizeTop . 'px ' . $sizeRight . 'px ' . $sizeBottom . 'px ' . $sizeLeft . 'px ' . $color;
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getMonthPadding($key)
    {
        $key = $this->mapKey($key);
        $sizeTop = $this->getAttribute('advanced_style_month_' . $key . '_px_top');
        $sizeRight = $this->getAttribute('advanced_style_month_' . $key . '_px_right');
        $sizeBottom = $this->getAttribute('advanced_style_month_' . $key . '_px_bottom');
        $sizeLeft = $this->getAttribute('advanced_style_month_' . $key . '_px_left');
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
        $size = $this->getAttribute('advanced_style_week_' . $key . '_px');
        $style = $this->getAttribute('advanced_style_week_' . $key . '_style');
        $color = $this->getAttribute('advanced_style_week_' . $key . '_color');
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