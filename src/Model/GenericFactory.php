<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

/**
 * Class PaginationFilterFactory
 * @package AllInData\Micro\Core\Model\Factory
 */
class GenericFactory extends AbstractFactory
{
    /**
     * @param array $data
     * @return AbstractModel
     */
    public function create(array $data = [])
    {
        return parent::create($data);
    }
}