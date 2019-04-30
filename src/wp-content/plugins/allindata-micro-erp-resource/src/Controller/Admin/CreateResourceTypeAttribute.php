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
 * Class CreateResourceTypeAttribute
 * @package AllInData\MicroErp\Resource\Controller\Admin
 */
class CreateResourceTypeAttribute extends AbstractAdminController
{
    const ACTION_SLUG = 'micro_erp_resource_admin_resource_type_attribute_create';

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
        $resourceTypeId = (int)$this->getParam('resourceTypeId');
        $type = $this->getParam('type');
        $name = $this->getParam('name');
        $sortOrder = (int)$this->getParam('sortOrder',0, FILTER_SANITIZE_NUMBER_INT);
        $isShownInGrid = $this->getParam('isShownInGrid') === 'true' ? true : false;
        $meta = $this->getParamAsArray('meta');

        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceTypeResource->loadById($resourceTypeId);
        if (!$resourceType->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource type with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceTypeId)
            );
        }

        /** @var ResourceTypeAttribute $resourceTypeAttribute */
        $resourceTypeAttribute = $this->entityResource->getModelFactory()->create();
        $resourceTypeAttribute
            ->setResourceTypeId($resourceTypeId)
            ->setType($type)
            ->setName($name)
            ->setIsShownInGrid($isShownInGrid)
            ->setSortOrder($sortOrder)
            ->setMeta($meta);

        $this->entityResource->save($resourceTypeAttribute);

        return $resourceTypeAttribute;
    }
}