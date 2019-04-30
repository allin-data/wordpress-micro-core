<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Attribute\Type;

use AllInData\MicroErp\Resource\Model\ResourceTypeAttribute;

/**
 * Interface TypeInterface
 * @package AllInData\MicroErp\Resource\Model\Attribute\Type
 */
interface TypeInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getTypeLabel(): string;

    /**
     * @param mixed $value
     * @return string
     */
    public function renderValue($value): string;

    /**
     * @param ResourceTypeAttribute $resourceTypeAttribute
     * @return string
     */
    public function renderFormLabelName(ResourceTypeAttribute $resourceTypeAttribute): string;

    /**
     * @param ResourceTypeAttribute $resourceTypeAttribute
     * @param string|int|bool|mixed $value
     * @return string
     */
    public function renderFormPart(ResourceTypeAttribute $resourceTypeAttribute, $value = null): string;
}