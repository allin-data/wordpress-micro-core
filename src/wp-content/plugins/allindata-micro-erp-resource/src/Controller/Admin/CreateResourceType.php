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
 * Class CreateResourceType
 * @package AllInData\MicroErp\Resource\Controller\Admin
 */
class CreateResourceType extends AbstractAdminController
{
    const ACTION_SLUG = 'micro_erp_resource_admin_resource_type_create';

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
        $name = $this->getParam('name');
        $label = $this->getParam('label');

        /** @var ResourceType $resourceType */
        $resourceType = $this->resource->getModelFactory()->create();
        $resourceType->setName($name)
            ->setLabel($label);

        $this->resource->save($resourceType);
    }
}