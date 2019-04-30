<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Controller\Admin;

use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Core\Controller\AbstractAdminController;
use AllInData\MicroErp\Resource\Model\ResourceType;
use AllInData\MicroErp\Resource\Model\ResourceAttributeValue;

/**
 * Class CreateResourceAttributeValue
 * @package AllInData\MicroErp\Resource\Controller\Admin
 */
class CreateResourceAttributeValue extends AbstractAdminController
{
    const ACTION_SLUG = 'micro_erp_resource_admin_resource_attribute_value_create';

    /**
     * @var GenericResource
     */
    private $entityResource;
    /**
     * @var GenericResource
     */
    private $resourceAttributeResource;

    /**
     * CreateResourceAttributeValue constructor.
     * @param GenericResource $entityResource
     * @param GenericResource $resourceAttributeResource
     */
    public function __construct(GenericResource $entityResource, GenericResource $resourceAttributeResource)
    {
        $this->entityResource = $entityResource;
        $this->resourceAttributeResource = $resourceAttributeResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $resourceId = (int)$this->getParam('resourceId');
        $resourceAttributeId = (int)$this->getParam('resourceAttributeId');
        $value = $this->getParam('value');

        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceAttributeResource->loadById($resourceAttributeId);
        if (!$resourceType->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource attribute with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceAttributeId)
            );
        }

        /** @var ResourceAttributeValue $resourceAttributeValue */
        $resourceAttributeValue = $this->entityResource->getModelFactory()->create();
        $resourceAttributeValue
            ->setResourceId($resourceId)
            ->setResourceAttributeId($resourceAttributeId)
            ->setValue($value);

        $this->entityResource->save($resourceAttributeValue);
    }
}