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
     * UpdateResource constructor.
     * @param GenericResource $resourceResource
     */
    public function __construct(GenericResource $resourceResource)
    {
        $this->resourceResource = $resourceResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $resourceId = (int)$this->getParam('resourceId');
        $name = $this->getParam('name');
        /** @var Resource $resource */
        $resource = $this->resourceResource->loadById($resourceId);
        if (!$resource->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceId)
            );
        }
        $resource
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