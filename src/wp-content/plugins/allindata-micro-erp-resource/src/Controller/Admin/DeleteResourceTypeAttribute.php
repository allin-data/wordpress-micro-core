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
 * Class DeleteResourceTypeAttribute
 * @package AllInData\MicroErp\Resource\Controller\Admin
 */
class DeleteResourceTypeAttribute extends AbstractAdminController
{
    const ACTION_SLUG = 'micro_erp_resource_admin_resource_type_attribute_delete';

    /**
     * @var GenericResource
     */
    private $entityResource;

    /**
     * DeleteResourceTypeAttribute constructor.
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

        /** @var ResourceTypeAttribute $resourceTypeAttribute */
        $resourceTypeAttribute = $this->entityResource->loadById($resourceTypeAttributeId);
        if (!$resourceTypeAttribute->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource type attribute with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceTypeAttributeId)
            );
        }

        $this->entityResource->deleteById($resourceTypeAttribute->getId());
    }
}