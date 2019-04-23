<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Factory;

use AllInData\MicroErp\Core\Model\AbstractFactory;
use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Mdm\Model\Organization as Entity;

/**
 * Class Organization
 * @package AllInData\MicroErp\Mdm\Model\Factory
 */
class Organization extends AbstractFactory
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