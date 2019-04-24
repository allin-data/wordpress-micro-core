<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block;

use AllInData\MicroErp\Core\Block\AbstractPaginationBlock;
use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Core\Model\PaginationInterface;
use AllInData\MicroErp\Resource\Controller\DeleteResource;
use AllInData\MicroErp\Resource\Controller\UpdateResource;
use AllInData\MicroErp\Resource\Model\Resource;
use AllInData\MicroErp\Resource\Model\ResourceType;

/**
 * Class GridResourceType
 * @package AllInData\MicroErp\Resource\Block
 */
class GridResource extends AbstractPaginationBlock
{
    /**
     * @var GenericCollection
     */
    private $resourceTypeCollection;

    /**
     * AbstractPaginationBlock constructor.
     * @param PaginationInterface $pagination
     * @param GenericCollection $resourceTypeCollection
     */
    public function __construct(
        PaginationInterface $pagination,
        GenericCollection $resourceTypeCollection
    ) {
        parent::__construct($pagination);
        $this->resourceTypeCollection = $resourceTypeCollection;
    }

    /**
     * @return Resource[]
     */
    public function getResources(): array
    {
        return $this->getPagination()->load();
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
        return $this->getAttribute('label');
    }

    /**
     * @return ResourceType[]
     */
    public function getResourceTypeSet()
    {
        $typeSet = $this->resourceTypeCollection->load(GenericCollection::NO_LIMIT);
        sort($typeSet);
        return $typeSet;
    }

    /**
     * @param int $typeId
     * @return string|null
     */
    public function getResourceTypeLabel(int $typeId)
    {
        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceTypeCollection->getResource()->loadById($typeId);
        return $resourceType->getLabel();
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