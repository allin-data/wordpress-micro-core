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
     * CreateResourceTypeAttribute constructor.
     * @param GenericResource $entityResource
     */
    public function __construct(GenericResource $entityResource)
    {
        $this->entityResource = $entityResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $resourceTypeAttributeId = $this->getParam('resourceTypeAttributeId');
        $type = $this->getParam('type');
        $name = $this->getParam('name');
        $sortOrder = (int)$this->getParam('sortOrder',0, FILTER_SANITIZE_NUMBER_INT);
        $isShownInGrid = $this->getParam('isShownInGrid') === 'true' ? true : false;
        $isUsedAsName = $this->getParam('isUsedAsName') === 'true' ? true : false;
        $meta = $this->getParamAsArray('meta');

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
            ->setIsShownInGrid($isShownInGrid)
            ->setIsUsedAsName($isUsedAsName)
            ->setSortOrder($sortOrder)
            ->setMeta($meta);

        $this->entityResource->save($resourceTypeAttribute);

        return $resourceTypeAttribute;
    }
}