<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Attribute\Type;

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
     * @return mixed
     */
    public function renderValue($value);
}