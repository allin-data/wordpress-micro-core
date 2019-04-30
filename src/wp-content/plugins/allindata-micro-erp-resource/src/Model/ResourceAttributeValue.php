<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model;

use AllInData\MicroErp\Core\Model\AbstractPostModel;

/**
 * Class ResourceAttributeValue
 * @package AllInData\MicroErp\Resource\Model
 */
class ResourceAttributeValue extends AbstractPostModel
{
    /**
     * @var int|null
     */
    private $resourceId;
    /**
     * @var int|null
     */
    private $resourceAttributeId;
    /**
     * @var string|int|float|bool|mixed|null
     */
    private $value;

    /**
     * @return int|null
     */
    public function getResourceId(): ?int
    {
        return $this->resourceId;
    }

    /**
     * @param int|null $resourceId
     * @return ResourceAttributeValue
     */
    public function setResourceId(?int $resourceId): ResourceAttributeValue
    {
        $this->resourceId = $resourceId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getResourceAttributeId(): ?int
    {
        return $this->resourceAttributeId;
    }

    /**
     * @param int|null $resourceAttributeId
     * @return ResourceAttributeValue
     */
    public function setResourceAttributeId(?int $resourceAttributeId): ResourceAttributeValue
    {
        $this->resourceAttributeId = $resourceAttributeId;
        return $this;
    }

    /**
     * @return bool|float|int|mixed|string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param bool|float|int|mixed|string|null $value
     * @return ResourceAttributeValue
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}