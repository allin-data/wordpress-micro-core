<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Controller\Admin;

use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Core\Controller\AbstractAdminController;
use AllInData\MicroErp\Resource\Model\ResourceType;

/**
 * Class UpdateResourceType
 * @package AllInData\MicroErp\Resource\Controller\Admin
 */
class UpdateResourceType extends AbstractAdminController
{
    const ACTION_SLUG = 'micro_erp_resource_admin_resource_type_update';

    /**
     * @var GenericResource
     */
    private $resource;

    /**
     * CreateResourceType constructor.
     * @param GenericResource $resource
     */
    public function __construct(GenericResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $resourceTypeId = $this->getParam('resourceTypeId');
        $label = $this->getParam('label');
        $isDisabled = $this->getParam('is_disabled') === 'on' ? true : false;

        /** @var ResourceType $resourceType */
        $resourceType = $this->resource->loadById($resourceTypeId);
        if (!$resourceType->getId()) {
            $this->throwErrorMessage(
                sprintf(__('Resource type with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceTypeId)
            );
        }

        $resourceType->setLabel($label)
            ->setIsDisabled($isDisabled);

        $this->resource->save($resourceType);
    }
}