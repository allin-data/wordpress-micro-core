<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Resource\Controller\CreateResource;
use AllInData\MicroErp\Resource\Model\ResourceType;

/**
 * Class FormCreateResourceType
 * @package AllInData\MicroErp\Resource\Block\Admin
 */
class FormCreateResource extends AbstractBlock
{
    /**
     * @var GenericResource
     */
    private $resourceTypeResource;

    /**
     * FormCreateResource constructor.
     * @param GenericResource $resourceTypeResource
     */
    public function __construct(GenericResource $resourceTypeResource)
    {
        $this->resourceTypeResource = $resourceTypeResource;
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
     * @return bool
     */
    public function isCreationAllowed(): bool
    {
        return !$this->getResourceType()->getIsDisabled();
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
        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceTypeResource->loadById($this->getResourceTypeId());
        return $resourceType;
    }

    /**
     * @return string
     */
    public function getCreateActionSlug()
    {
        return CreateResource::ACTION_SLUG;
    }
}