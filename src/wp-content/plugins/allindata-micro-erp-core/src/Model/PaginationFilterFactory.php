<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

use AllInData\MicroErp\Core\Model\GenericPaginationFilter as Entity;

/**
 * Class PaginationFilterFactory
 * @package AllInData\MicroErp\Core\Model\Factory
 */
class PaginationFilterFactory extends AbstractFactory
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