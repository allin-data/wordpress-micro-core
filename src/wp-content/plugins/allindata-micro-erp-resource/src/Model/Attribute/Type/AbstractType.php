<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Attribute\Type;

use AllInData\MicroErp\Core\Model\AbstractModel;

/**
 * Class AbstractType
 * @package AllInData\MicroErp\Resource\Model\Attribute\Type
 */
abstract class AbstractType extends AbstractModel
{
    const TYPE = '';

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }

    /**
     * @return string
     */
    public function getTypeLabel(): string
    {
        return ucfirst(static::TYPE);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    abstract public function renderValue($value);
}