<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Model\Factory;

use AllInData\MicroErp\Core\Model\AbstractFactory;
use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Planning\Model\Schedule as Entity;

/**
 * Class Schedule
 * @package AllInData\MicroErp\Planning\Model\Factory
 */
class Schedule extends AbstractFactory
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