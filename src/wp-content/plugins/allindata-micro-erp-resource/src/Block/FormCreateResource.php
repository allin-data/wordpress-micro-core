<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Resource\Controller\CreateResource;
use AllInData\MicroErp\Resource\Model\Attribute\Type\TypeInterface;
use AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute;
use AllInData\MicroErp\Resource\Model\Factory\AttributeTypeFactory;
use AllInData\MicroErp\Resource\Model\ResourceType;

/**
 * Class FormCreateResourceType
 * @package AllInData\MicroErp\Resource\Block\Admin
 */
class FormCreateResource extends AbstractBlock
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
     * @var TypeInterface[]|null
     */
    private $attributeTypeSet;

    /**
     * FormCreateResource constructor.
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
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttribute('title');
    }

    /**
     * @return string
     */
    public function getResourceLabel()
    {
        return $this->getResourceType()->getLabel();
    }

    /**
     * @return bool
     */
    public function isCreationAllowed(): bool
    {
        return !$this->getResourceType()->getIsDisabled();
    }

    /**
     * @return int
     */
    public function getResourceTypeId()
    {
        return (int)$this->getAttribute('resource_type_id');
    }

    /**
     * @return ResourceType|null
     */
    public function getResourceType()
    {
        /** @var ResourceType $resourceType */
        $resourceType = $this->resourceTypeResource->loadById($this->getResourceTypeId());
        return $resourceType;
    }

    /**
     * @param ResourceType $resourceType
     * @return \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute[]
     */
    public function getResourceTypeAttributes(ResourceType $resourceType): array
    {
        $unsortedResult = $this->attributeCollection->load(ResourceTypeAttribute::NO_LIMIT, 0,
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

        $resourceTypeAttributes = [];
        foreach ($unsortedResult as $resourceTypeAttribute) {
            /** @var \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute $resourceTypeAttribute */
            $sortValue = $resourceTypeAttribute->getSortOrder();
            while (isset($this->resourceTypeAttributes[$sortValue])) {
                ++$sortValue;
            }
            $resourceTypeAttribute->setSortOrder($sortValue);
            $resourceTypeAttributes[$sortValue] = $resourceTypeAttribute;
        }
        ksort($resourceTypeAttributes, SORT_NUMERIC);

        return $resourceTypeAttributes;
    }

    /**
     * @param \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute $resourceTypeAttribute
     * @return TypeInterface
     */
    public function getResourceAttributeType(
        \AllInData\MicroErp\Resource\Model\ResourceTypeAttribute $resourceTypeAttribute
    ): TypeInterface {
        if (!$this->attributeTypeSet) {
            $this->attributeTypeSet = $this->attributeTypeFactory->getTypes();
        }

        if (!isset($this->attributeTypeSet[$resourceTypeAttribute->getType()])) {
            throw new \InvalidArgumentException(
                sprintf(
                    __('Resource attribute type with id "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
                    $resourceTypeAttribute->getType()
                )
            );
        }
        return $this->attributeTypeSet[$resourceTypeAttribute->getType()];
    }

    /**
     * @return string
     */
    public function getCreateActionSlug()
    {
        return CreateResource::ACTION_SLUG;
    }
}