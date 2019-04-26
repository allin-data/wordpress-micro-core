<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Controller\Admin;

use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Core\Controller\AbstractAdminController;
use AllInData\MicroErp\Resource\Model\ResourceType;
use AllInData\MicroErp\Resource\Model\ResourceTypeAttribute;

/**
 * Class UpdateResourceTypeAttribute
 * @package AllInData\MicroErp\Resource\Controller\Admin
 */
class UpdateResourceTypeAttribute extends AbstractAdminController
{
    const ACTION_SLUG = 'micro_erp_resource_admin_resource_type_attribute_update';

    /**
     * @var GenericResource
     */
    private $entityResource;
    /**
     * @var GenericResource
     */
    private $resourceTypeResource;

    /**
     * CreateResourceTypeAttribute constructor.
     * @param GenericResource $entityResource
     * @param GenericResource $resourceTypeResource
     */
    public function __construct(GenericResource $entityResource, GenericResource $resourceTypeResource)
    {
        $this->entityResource = $entityResource;
        $this->resourceTypeResource = $resourceTypeResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $resourceTypeAttributeId = $this->getParam('resourceTypeAttributeId');
        $resourceTypeId = (int)$this->getParam('resourceTypeId');
        $type = $this->getParam('type');
        $name = $this->getParam('name');
        $meta = $this->getParamAsArray('meta');

        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceTypeResource->loadById($resourceTypeId);
        if (!$resourceType->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource type with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceTypeId)
            );
        }

        /** @var ResourceType $resourceType */
        $resourceTypeAttribute = $this->entityResource->loadById($resourceTypeAttributeId);
        if (!$resourceTypeAttribute->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource type attribute with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceTypeAttributeId)
            );
        }

        /** @var ResourceTypeAttribute $resourceTypeAttribute */
        $resourceTypeAttribute
            ->setType($type)
            ->setName($name)
            ->setMeta($meta);

        $this->entityResource->save($resourceTypeAttribute);
    }
}