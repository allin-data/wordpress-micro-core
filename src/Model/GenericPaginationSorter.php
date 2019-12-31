<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

/**
 * Class GenericPaginationSorter
 * @package AllInData\Micro\Core\Model
 */
class GenericPaginationSorter extends AbstractModel implements PaginationSorterInterface
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $direction;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return GenericPaginationSorter
     */
    public function setName(?string $name): GenericPaginationSorter
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * @param string|null $direction
     * @return GenericPaginationSorter
     */
    public function setDirection(?string $direction): GenericPaginationSorter
    {
        $this->direction = $direction;
        return $this;
    }
}