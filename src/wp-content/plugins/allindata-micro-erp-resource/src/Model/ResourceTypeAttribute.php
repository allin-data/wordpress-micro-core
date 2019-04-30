<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model;

use AllInData\MicroErp\Core\Model\AbstractPostModel;

/**
 * Class ResourceType
 * @package AllInData\MicroErp\Resource\Model
 */
class ResourceTypeAttribute extends AbstractPostModel
{
    /**
     * @var string|int|null
     */
    private $resourceTypeId;
    /**
     * @var string|null
     */
    private $type;
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var bool|int|null
     */
    private $isShownInGrid;
    /**
     * @var int|null
     */
    private $sortOrder;
    /**
     * @var array|null
     */
    private $meta;

    /**
     * @return int|string|null
     */
    public function getResourceTypeId()
    {
        return $this->resourceTypeId;
    }

    /**
     * @param int|string|null $resourceTypeId
     * @return ResourceTypeAttribute
     */
    public function setResourceTypeId($resourceTypeId)
    {
        $this->resourceTypeId = $resourceTypeId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return ResourceTypeAttribute
     */
    public function setType(?string $type): ResourceTypeAttribute
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ResourceTypeAttribute
     */
    public function setName(?string $name): ResourceTypeAttribute
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return bool|int|null
     */
    public function getIsShownInGrid()
    {
        return $this->isShownInGrid;
    }

    /**
     * @param bool|int|null $isShownInGrid
     * @return ResourceTypeAttribute
     */
    public function setIsShownInGrid($isShownInGrid)
    {
        $this->isShownInGrid = $isShownInGrid;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    /**
     * @param int|null $sortOrder
     * @return ResourceTypeAttribute
     */
    public function setSortOrder(?int $sortOrder): ResourceTypeAttribute
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getMeta(): ?array
    {
        return $this->meta;
    }

    /**
     * @param array|null $meta
     * @return ResourceTypeAttribute
     */
    public function setMeta(?array $meta): ResourceTypeAttribute
    {
        $this->meta = $meta;
        return $this;
    }
}