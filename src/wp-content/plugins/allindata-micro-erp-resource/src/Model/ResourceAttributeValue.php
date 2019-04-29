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
    private $attributeTypeId;
    /**
     * @var string|null
     */
    private $value;

    /**
     * @return int|null
     */
    public function getAttributeTypeId(): ?int
    {
        return $this->attributeTypeId;
    }

    /**
     * @param int|null $attributeTypeId
     * @return ResourceAttributeValue
     */
    public function setAttributeTypeId(?int $attributeTypeId): ResourceAttributeValue
    {
        $this->attributeTypeId = $attributeTypeId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param string|null $value
     * @return ResourceAttributeValue
     */
    public function setValue(?string $value): ResourceAttributeValue
    {
        $this->value = $value;
        return $this;
    }
}