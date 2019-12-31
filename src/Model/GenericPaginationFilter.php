<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

/**
 * Class GenericPaginationFilter
 * @package AllInData\Micro\Core\Model
 */
class GenericPaginationFilter extends AbstractModel implements PaginationFilterInterface
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $value;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return GenericPaginationFilter
     */
    public function setName(?string $name): GenericPaginationFilter
    {
        $this->name = $name;
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
     * @return GenericPaginationFilter
     */
    public function setValue(?string $value): GenericPaginationFilter
    {
        $this->value = $value;
        return $this;
    }
}