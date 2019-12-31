<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

use AllInData\Micro\Core\Model\GenericPaginationSorter as Entity;

/**
 * Class PaginationSorterFactory
 * @package AllInData\Micro\Core\Model\Factory
 */
class PaginationSorterFactory extends AbstractFactory
{
    /**
     * @param array $data
     * @return Entity
     */
    public function create(array $data = []): AbstractModel
    {
        return parent::create($data);
    }
}