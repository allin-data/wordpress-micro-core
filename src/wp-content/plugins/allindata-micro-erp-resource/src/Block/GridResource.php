<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block;

use AllInData\MicroErp\Core\Block\AbstractPaginationBlock;
use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Core\Model\PaginationInterface;
use AllInData\MicroErp\Resource\Controller\DeleteResource;
use AllInData\MicroErp\Resource\Controller\UpdateResource;
use AllInData\MicroErp\Resource\Model\Attribute\Type\TypeInterface;
use AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute;
use AllInData\MicroErp\Resource\Model\Factory\AttributeTypeFactory;
use AllInData\MicroErp\Resource\Model\Resource;
use AllInData\MicroErp\Resource\Model\ResourceAttributeValue;
use AllInData\MicroErp\Resource\Model\ResourceType;

/**
 * Class GridResourceType
 * @package AllInData\MicroErp\Resource\Block
 */
class GridResource extends AbstractPaginationBlock
{
    /**
     * @var GenericResource
     */
    private $resourceTypeResource;
    /**
     * @var ResourceType
     */
    private $resourceType;
    /**
     * @var ResourceTypeAttribute
     */
    private $attributeCollection;
    /**
     * @var AttributeTypeFactory
     */
    private $attributeTypeFactory;
    /**
     * @var GenericCollection
     */
    private $resourceAttributeValueCollection;

    /**
     * GridResource constructor.
     * @param PaginationInterface $pagination
     * @param GenericResource $resourceTypeResource
     * @param ResourceTypeAttribute $attributeCollection
     * @param AttributeTypeFactory $attributeTypeFactory
     * @param GenericCollection $resourceAttributeValueCollection
     */
    public function __construct(
        PaginationInterface $pagination,
        GenericResource $resourceTypeResource,
        ResourceTypeAttribute $attributeCollection,
        AttributeTypeFactory $attributeTypeFactory,
        GenericCollection $resourceAttributeValueCollection
    ) {
        parent::__construct($pagination);
        $this->resourceTypeResource = $resourceTypeResource;
        $this->attributeCollection = $attributeCollection;
        $this->attributeTypeFactory = $attributeTypeFactory;
        $this->resourceAttributeValueCollection = $resourceAttributeValueCollection;
    }

    /**
     * @return Resource[]
     */
    public function getResources(): array
    {
        $metaQuery = [
            [
                'key' => 'type_id',
                'value' => $this->getResourceTypeId(),
                'compare' => '=',
            ],
        ];
        return $this->getPagination()->load([
            'meta_query' => $metaQuery
        ]);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }

    /**
     * @return string
     */
    public function getResourceLabel()
    {
        return $this->getResourceType()->getLabel();
    }

    /**
     * @return int
     */
    public function getResourceTypeId()
    {
        return (int)$this->getAttribute('resource_type_id');
    }

    /**
     * @return ResourceType|null
     */
    public function getResourceType()
    {
        if ($this->resourceType) {
            return $this->resourceType;
        }
        $this->resourceType = $this->resourceTypeResource->loadById($this->getResourceTypeId());
        return $this->resourceType;
    }

    /**
     * @param ResourceType $resourceType
     * @return \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute[]
     */
    public function getResourceTypeAttributes(ResourceType $resourceType): array
    {
        $unsortedResult = $this->attributeCollection->load(ResourceTypeAttribute::NO_LIMIT, 0,
            [
                'meta_query' => [
                    [
                        'key' => 'resource_type_id',
                        'value' => $resourceType->getId(),
                        'compare' => '=',
                    ],
                ]
            ]
        );

        $resourceTypeAttributes = [];
        foreach ($unsortedResult as $resourceTypeAttribute) {
            /** @var \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute $resourceTypeAttribute */
            $sortValue = $resourceTypeAttribute->getSortOrder();
            while (isset($this->resourceTypeAttributes[$sortValue])) {
                ++$sortValue;
            }
            $resourceTypeAttribute->setSortOrder($sortValue);
            $resourceTypeAttributes[$sortValue] = $resourceTypeAttribute;
        }
        ksort($resourceTypeAttributes, SORT_NUMERIC);

        return $resourceTypeAttributes;
    }

    /**
     * @return TypeInterface[]
     */
    public function getAttributeTypes(): array
    {
        return $this->attributeTypeFactory->getTypes();
    }

    /**
     * @param \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute $resourceTypeAttribute
     * @return TypeInterface
     */
    public function getAttributeType(\AllInData\MicroErp\Resource\Model\ResourceTypeAttribute $resourceTypeAttribute): TypeInterface
    {
        return $this->attributeTypeFactory->getType($resourceTypeAttribute->getType());
    }

    /**
     * @param Resource $resource
     * @param \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute $resourceTypeAttribute
     * @return ResourceAttributeValue|null
     */
    public function getResourceAttributeValue(
        Resource $resource,
        \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute $resourceTypeAttribute
    ): ?ResourceAttributeValue {
        /** @var ResourceAttributeValue[] $resourceAttributeValueSet */
        $resourceAttributeValueSet = $this->resourceAttributeValueCollection->load(
            GenericCollection::NO_LIMIT,
            0,
            [
                'meta_query' => [
                    [
                        'key' => 'resource_id',
                        'value' => $resource->getId(),
                        'compare' => '=',
                    ],
                ]
            ]
        );

        foreach ($resourceAttributeValueSet as $resourceAttributeValue) {
            if ($resourceAttributeValue->getResourceAttributeId() == $resourceTypeAttribute->getId()) {
                return $resourceAttributeValue;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function getUpdateActionSlug()
    {
        return UpdateResource::ACTION_SLUG;
    }

    /**
     * @return string
     */
    public function getDeleteActionSlug()
    {
        return DeleteResource::ACTION_SLUG;
    }
}