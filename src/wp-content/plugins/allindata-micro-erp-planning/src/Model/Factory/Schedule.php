<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Model\Factory;

use AllInData\MicroErp\Core\Model\AbstractFactory;
use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Core\Model\GenericFactory;
use AllInData\MicroErp\Planning\Model\Schedule as Entity;

/**
 * Class Schedule
 * @package AllInData\MicroErp\Planning\Model\Factory
 */
class Schedule extends AbstractFactory
{
    /**
     * @var ScheduleMeta
     */
    private $scheduleMetaFactory;

    /**
     * AbstractFactory constructor.
     * @param string $modelClass
     * @param ScheduleMeta $scheduleMetaFactory
     */
    public function __construct(string $modelClass, ScheduleMeta $scheduleMetaFactory)
    {
        parent::__construct($modelClass);
        $this->scheduleMetaFactory = $scheduleMetaFactory;
    }

    /**
     * @param array $data
     * @return Entity
     */
    public function create(array $data = []): AbstractModel
    {
        if (isset($data['raw']) && is_array($data['raw'])) {
            $data['raw'] = $this->scheduleMetaFactory->create($data['raw']);
        } else {
            $data['raw'] = null;
        }
        return parent::create($data);
    }
}