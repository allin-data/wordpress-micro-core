<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Controller;

use AllInData\MicroErp\Core\Controller\AbstractController;
use AllInData\MicroErp\Core\Model\GenericFactory;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Resource\Model\Resource;
use AllInData\MicroErp\Resource\Model\ResourceAttributeValue;

/**
 * Class CreateResource
 * @package AllInData\MicroErp\Resource\Controller
 */
class CreateResource extends AbstractController
{
    const ACTION_SLUG = 'micro_erp_resource_create_resource';

    /**
     * @var GenericResource
     */
    private $resourceResource;
    /**
     * @var GenericResource
     */
    private $resourceTypeResource;
    /**
     * @var GenericFactory
     */
    private $resourceAttributeValueFactory;
    /**
     * @var GenericResource
     */
    private $resourceAttributeValueResource;

    /**
     * CreateResource constructor.
     * @param GenericResource $resourceResource
     * @param GenericResource $resourceTypeResource
     * @param GenericFactory $resourceAttributeValueFactory
     * @param GenericResource $resourceAttributeValueResource
     */
    public function __construct(
        GenericResource $resourceResource,
        GenericResource $resourceTypeResource,
        GenericFactory $resourceAttributeValueFactory,
        GenericResource $resourceAttributeValueResource
    ) {
        $this->resourceResource = $resourceResource;
        $this->resourceTypeResource = $resourceTypeResource;
        $this->resourceAttributeValueFactory = $resourceAttributeValueFactory;
        $this->resourceAttributeValueResource = $resourceAttributeValueResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $typeId = (int)$this->getParam('typeId');
        $attributes = $this->getParamAsArray('attributes');

        $resourceType = $this->resourceTypeResource->loadById($typeId);
        if (!$resourceType->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource type with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $typeId)
            );
        }

        /** @var Resource $resource */
        $resource = $this->resourceResource->getModelFactory()->create();
        $resource
            ->setTypeId($typeId);
        $this->resourceResource->save($resource);

        foreach ($attributes as $attributeId => $attributeValue) {
            /** @var ResourceAttributeValue $resourceAttributeValue */
            $resourceAttributeValue = $this->resourceAttributeValueFactory->create();
            $resourceAttributeValue
                ->setResourceId($resource->getId())
                ->setResourceAttributeId($attributeId)
                ->setValue($attributeValue);
            $this->resourceAttributeValueResource->save($resourceAttributeValue);
        }

        return $resource->getId();
    }

    /**
     * @inheritDoc
     */
    protected function getRequiredCapabilitySet(): array
    {
        return [\AllInData\MicroErp\Resource\Model\Capability\CreateResource::CAPABILITY];
    }
}