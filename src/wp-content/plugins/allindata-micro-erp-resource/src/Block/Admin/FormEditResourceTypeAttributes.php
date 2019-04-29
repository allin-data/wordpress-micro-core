<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block\Admin;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Resource\Controller\Admin\CreateResourceTypeAttribute;
use AllInData\MicroErp\Resource\Controller\Admin\DeleteResourceTypeAttribute;
use AllInData\MicroErp\Resource\Controller\Admin\UpdateResourceTypeAttribute;
use AllInData\MicroErp\Resource\Model\Attribute\Type\TypeInterface;
use AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute;
use AllInData\MicroErp\Resource\Model\Factory\AttributeTypeFactory;
use AllInData\MicroErp\Resource\Model\ResourceType;
use InvalidArgumentException;

/**
 * Class FormAddResourceTypeAttributes
 * @package AllInData\MicroErp\Resource\Block\Admin
 */
class FormEditResourceTypeAttributes extends AbstractBlock
{
    /**
     * @var GenericResource
     */
    private $resourceTypeResource;
    /**
     * @var ResourceTypeAttribute
     */
    private $attributeCollection;
    /**
     * @var AttributeTypeFactory
     */
    private $attributeTypeFactory;
    /**
     * @var ResourceType
     */
    private $resourceType;

    /**
     * FormEditResourceTypeAttributes constructor.
     * @param GenericResource $resourceTypeResource
     * @param ResourceTypeAttribute $attributeCollection
     * @param AttributeTypeFactory $attributeTypeFactory
     */
    public function __construct(
        GenericResource $resourceTypeResource,
        ResourceTypeAttribute $attributeCollection,
        AttributeTypeFactory $attributeTypeFactory
    ) {
        $this->resourceTypeResource = $resourceTypeResource;
        $this->attributeCollection = $attributeCollection;
        $this->attributeTypeFactory = $attributeTypeFactory;
    }

    /**
     * @return ResourceType
     */
    public function getResourceType(): ResourceType
    {
        if ($this->resourceType) {
            return $this->resourceType;
        }

        $resourceTypeId = (int)$this->getAttribute('resource_type_id');
        if (!$this->resourceTypeResource->existsById($resourceTypeId)) {
            throw new InvalidArgumentException(
                sprintf(__('Resource type with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN), $resourceTypeId)
            );
        }

        /** @var ResourceType $resourceType */
        $this->resourceType = $this->resourceTypeResource->loadById($resourceTypeId);
        return $this->resourceType;
    }

    /**
     * @return \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute[]
     */
    public function getResourceTypeAttributes(): array
    {
        $resourceType = $this->getResourceType();

        return $this->attributeCollection->load(ResourceTypeAttribute::NO_LIMIT, 0,
            [
                'meta_query' => [
                    [
                        'key' => 'resource_type_id',
                        'value' => $resourceType->getId(),
                        'compare' => '=',
                    ],
                ]
            ]
        );
    }

    /**
     * @return TypeInterface[]
     */
    public function getAttributeTypes(): array
    {
        return $this->attributeTypeFactory->getTypes();
    }

    /**
     * @return string
     */
    public function getCreateActionSlug()
    {
        return CreateResourceTypeAttribute::ACTION_SLUG;
    }

    /**
     * @return string
     */
    public function getUpdateActionSlug()
    {
        return UpdateResourceTypeAttribute::ACTION_SLUG;
    }

    /**
     * @return string
     */
    public function getDeleteActionSlug()
    {
        return DeleteResourceTypeAttribute::ACTION_SLUG;
    }
}