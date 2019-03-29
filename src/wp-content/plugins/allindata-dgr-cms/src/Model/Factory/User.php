<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model\Factory;

use AllInData\Dgr\Core\Model\AbstractFactory;
use AllInData\Dgr\Core\Model\AbstractModel;
use AllInData\Dgr\Cms\Model\User as Entity;

/**
 * Class User
 * @package AllInData\Dgr\Cms\Model\Factory
 */
class User extends AbstractFactory
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