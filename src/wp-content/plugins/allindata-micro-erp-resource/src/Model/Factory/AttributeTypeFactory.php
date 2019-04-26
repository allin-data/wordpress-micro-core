<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model\Factory;

use AllInData\MicroErp\Resource\Model\Attribute\Type\TypeInterface;

/**
 * Class AttributeTypeFactory
 * @package AllInData\MicroErp\Resource\Model\Factory
 */
class AttributeTypeFactory
{
    /**
     * @var TypeInterface[]
     */
    private $types;

    /**
     * ResourceTypeAttribute constructor.
     * @param TypeInterface[] $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasType(string $key): bool
    {
        return isset($this->types[$key]);
    }

    /**
     * @param string $key
     * @return TypeInterface
     */
    public function getType(string $key): TypeInterface
    {
        if ($this->hasType($key)) {
            throw new \InvalidArgumentException(
                sprintf(
                    __('Attribute type with key "%s" does not exist', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
                    $key
                )
            );
        }
        return $this->types[$key];
    }

    /**
     *
     * @return TypeInterface[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}