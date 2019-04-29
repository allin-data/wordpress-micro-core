<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block;

use AllInData\MicroErp\Core\Block\AbstractPaginationBlock;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Core\Model\PaginationInterface;
use AllInData\MicroErp\Resource\Controller\DeleteResource;
use AllInData\MicroErp\Resource\Controller\UpdateResource;
use AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute;
use AllInData\MicroErp\Resource\Model\Resource;
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
     * GridResource constructor.
     * @param PaginationInterface $pagination
     * @param GenericResource $resourceTypeResource
     * @param ResourceTypeAttribute $attributeCollection
     */
    public function __construct(
        PaginationInterface $pagination,
        GenericResource $resourceTypeResource,
        ResourceTypeAttribute $attributeCollection
    ) {
        parent::__construct($pagination);
        $this->resourceTypeResource = $resourceTypeResource;
        $this->attributeCollection = $attributeCollection;
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
        return $this->attributeCollection->load(ResourceTypeAttribute::NO_LIMIT, 0,
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