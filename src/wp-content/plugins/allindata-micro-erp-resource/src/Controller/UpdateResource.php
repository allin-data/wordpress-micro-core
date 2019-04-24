<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Controller;

use AllInData\MicroErp\Core\Controller\AbstractController;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Resource\Model\Resource;

/**
 * Class UpdateResource
 * @package AllInData\MicroErp\Resource\Controller
 */
class UpdateResource extends AbstractController
{
    const ACTION_SLUG = 'micro_erp_resource_update_resource';

    /**
     * @var GenericResource
     */
    private $resourceResource;
    /**
     * @var GenericResource
     */
    private $resourceTypeResource;

    /**
     * UpdateResource constructor.
     * @param GenericResource $resourceResource
     * @param GenericResource $resourceTypeResource
     */
    public function __construct(GenericResource $resourceResource, GenericResource $resourceTypeResource)
    {
        $this->resourceResource = $resourceResource;
        $this->resourceTypeResource = $resourceTypeResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $resourceId = (int)$this->getParam('resourceId');
        $typeId = (int)$this->getParam('typeId');
        $name = $this->getParam('name');

        $resourceType = $this->resourceTypeResource->loadById($typeId);
        if (!$resourceType->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource type with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $typeId)
            );
        }

        /** @var Resource $resource */
        $resource = $this->resourceResource->loadById($resourceId);
        if (!$resource->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceId)
            );
        }
        $resource
            ->setTypeId($typeId)
            ->setName($name);
        $this->resourceResource->save($resource);
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function getRequiredCapabilitySet(): array
    {
        return [\AllInData\MicroErp\Resource\Model\Capability\UpdateResource::CAPABILITY];
    }
}