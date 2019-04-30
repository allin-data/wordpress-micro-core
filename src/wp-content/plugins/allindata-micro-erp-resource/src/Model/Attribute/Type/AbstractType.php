<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Attribute\Type;

use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Resource\Model\ResourceTypeAttribute;

/**
 * Class AbstractType
 * @package AllInData\MicroErp\Resource\Model\Attribute\Type
 */
abstract class AbstractType extends AbstractModel implements TypeInterface
{
    const TYPE = '';

    /**
     * @inheritDoc
     */
    public function getType(): string
    {
        return static::TYPE;
    }

    /**
     * @inheritDoc
     */
    public function getTypeLabel(): string
    {
        return ucfirst(static::TYPE);
    }

    /**
     * @inheritDoc
     */
    abstract public function renderValue($value);

    /**
     * @inheritDoc
     */
    public function renderFormLabelName(ResourceTypeAttribute $resourceTypeAttribute): string
    {
        return sprintf('attributes[%1$s]', $resourceTypeAttribute->getId());
    }

    /**
     * @inheritDoc
     */
    public function renderFormPart(ResourceTypeAttribute $resourceTypeAttribute, $value = null): string
    {
        return sprintf(
            '<input type="%1$s" name="%2$s" class="form-control" value="%3$s"/>',
            $this->getType(),
            $this->renderFormLabelName($resourceTypeAttribute),
            $value ?? ''
        );
    }
}