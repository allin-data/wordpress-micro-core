<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Controller;

use AllInData\MicroErp\Core\Controller\AbstractController;
use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Resource\Model\Resource;
use AllInData\MicroErp\Resource\Model\ResourceAttributeValue;

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
     * @var GenericCollection
     */
    private $resourceAttributeValueCollection;

    /**
     * DeleteResource constructor.
     * @param GenericResource $resourceResource
     * @param GenericCollection $resourceAttributeValueCollection
     */
    public function __construct(GenericResource $resourceResource, GenericCollection $resourceAttributeValueCollection)
    {
        $this->resourceResource = $resourceResource;
        $this->resourceAttributeValueCollection = $resourceAttributeValueCollection;
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

        $resourceAttributeValues = $this->resourceAttributeValueCollection->load(
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
        foreach ($resourceAttributeValues as $resourceAttributeValue) {
            /** @var ResourceAttributeValue $resourceAttributeValue */
            $this->resourceAttributeValueCollection->getResource()->deleteById($resourceAttributeValue->getId());
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