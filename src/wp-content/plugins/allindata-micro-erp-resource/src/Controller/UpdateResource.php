<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Controller;

use AllInData\MicroErp\Core\Controller\AbstractController;
use AllInData\MicroErp\Core\Model\GenericOwnedCollection;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Resource\Model\Resource;
use AllInData\MicroErp\Resource\Model\ResourceAttributeValue;

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
     * @var GenericOwnedCollection
     */
    private $resourceAttributeValueCollection;

    /**
     * UpdateResource constructor.
     * @param GenericResource $resourceResource
     * @param GenericOwnedCollection $resourceAttributeValueCollection
     */
    public function __construct(GenericResource $resourceResource, GenericOwnedCollection $resourceAttributeValueCollection)
    {
        $this->resourceResource = $resourceResource;
        $this->resourceAttributeValueCollection = $resourceAttributeValueCollection;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $resourceId = (int)$this->getParam('resourceId');
        $attributes = $this->getParamAsArray('attributes');

        /** @var Resource $resource */
        $resource = $this->resourceResource->loadById($resourceId);
        if (!$resource->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceId)
            );
        }
        // no direct properties to update
        // $this->resourceResource->save($resource);

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

        //@TODO add newly added resource attribute values to the resource entity
        foreach ($resourceAttributeValues as $resourceAttributeValue) {
            /** @var ResourceAttributeValue $resourceAttributeValue */
            if (!isset($attributes[$resourceAttributeValue->getResourceAttributeId()])) {
                continue;
            }
            $resourceAttributeValue->setValue($attributes[$resourceAttributeValue->getResourceAttributeId()]);
            $this->resourceAttributeValueCollection->getResource()->save($resourceAttributeValue);
        }

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