<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Resource\Controller\CreateResource;
use AllInData\MicroErp\Resource\Model\ResourceType;

/**
 * Class FormCreateResourceType
 * @package AllInData\MicroErp\Resource\Block\Admin
 */
class FormCreateResource extends AbstractBlock
{
    /**
     * @var GenericCollection
     */
    private $resourceTypeCollection;

    /**
     * FormCreateResource constructor.
     * @param GenericCollection $resourceTypeCollection
     */
    public function __construct(GenericCollection $resourceTypeCollection)
    {
        $this->resourceTypeCollection = $resourceTypeCollection;
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
     * @return string
     */
    public function getCreateActionSlug()
    {
        return CreateResource::ACTION_SLUG;
    }
}