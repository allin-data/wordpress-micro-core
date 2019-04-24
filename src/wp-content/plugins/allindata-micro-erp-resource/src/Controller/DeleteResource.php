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
 * Class DeleteResource
 * @package AllInData\MicroErp\Resource\Controller
 */
class DeleteResource extends AbstractController
{
    const ACTION_SLUG = 'micro_erp_resource_delete_resource';

    /**
     * @var GenericResource
     */
    private $resourceResource;

    /**
     * DeleteResource constructor.
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
        $resourceId = $this->getParam('resourceId');

        /** @var Resource $resource */
        $resource = $this->resourceResource->loadById($resourceId);
        if (!$resource->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceId)
            );
        }
        return $this->resourceResource->deleteById($resource->getId());
    }

    /**
     * @inheritDoc
     */
    protected function getRequiredCapabilitySet(): array
    {
        return [\AllInData\MicroErp\Resource\Model\Capability\DeleteResource::CAPABILITY];
    }
}