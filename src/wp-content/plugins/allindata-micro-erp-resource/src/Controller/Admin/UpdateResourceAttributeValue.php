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
 * Class UpdateResourceAttributeValue
 * @package AllInData\MicroErp\Resource\Controller\Admin
 */
class UpdateResourceAttributeValue extends AbstractAdminController
{
    const ACTION_SLUG = 'micro_erp_resource_admin_resource_attribute_value_update';

    /**
     * @var GenericResource
     */
    private $entityResource;

    /**
     * CreateResourceAttributeValue constructor.
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
        $resourceAttributeValueId = (int)$this->getParam('resourceAttributeValueId');
        $value = $this->getParam('value');

        /** @var ResourceAttributeValue $resourceAttributeValue */
        $resourceAttributeValue = $this->entityResource->loadById($resourceAttributeValueId);
        if (!$resourceAttributeValue->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource attribute value with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceAttributeValueId)
            );
        }

        $resourceAttributeValue
            ->setValue($value);

        $this->entityResource->save($resourceAttributeValue);
    }
}