<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Controller;

use AllInData\MicroErp\Core\Controller\AbstractController;
use AllInData\MicroErp\Core\Model\GenericOwnedCollection;
use AllInData\MicroErp\Core\Model\GenericOwnedResource;
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
     * @var GenericOwnedResource
     */
    private $resourceResource;
    /**
     * @var GenericOwnedCollection
     */
    private $resourceAttributeValueCollection;

    /**
     * DeleteResource constructor.
     * @param GenericOwnedResource $resourceResource
     * @param GenericOwnedCollection $resourceAttributeValueCollection
     */
    public function __construct(GenericOwnedResource $resourceResource, GenericOwnedCollection $resourceAttributeValueCollection)
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
            GenericOwnedCollection::NO_LIMIT,
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