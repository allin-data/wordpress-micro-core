<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Model\Factory;

use AllInData\MicroErp\Core\Model\AbstractFactory;
use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Planning\Model\ScheduleMeta as Entity;

/**
 * Class ScheduleMeta
 * @package AllInData\MicroErp\Planning\Model\Factory
 */
class ScheduleMeta extends AbstractFactory
{
    /**
     * @var ScheduleMetaCreator
     */
    private $scheduleMetaCreatorFactory;

    /**
     * AbstractFactory constructor.
     * @param string $modelClass
     * @param ScheduleMetaCreator $scheduleMetaCreatorFactory
     */
    public function __construct(string $modelClass, ScheduleMetaCreator $scheduleMetaCreatorFactory)
    {
        parent::__construct($modelClass);
        $this->scheduleMetaCreatorFactory = $scheduleMetaCreatorFactory;
    }

    /**
     * @param array $data
     * @return Entity
     */
    public function create(array $data = []): AbstractModel
    {
        if (isset($data['creator']) && is_array($data['creator'])) {
            $data['creator'] = $this->scheduleMetaCreatorFactory->create($data['creator']);
        } else {
            $data['creator'] = null;
        }
        return parent::create($data);
    }
}