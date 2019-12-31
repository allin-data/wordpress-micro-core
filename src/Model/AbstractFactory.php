<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

/**
 * Class AbstractFactory
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractFactory
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * AbstractFactory constructor.
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param array $data
     * @return AbstractModel
     */
    public function create(array $data = [])
    {
        if (!(class_exists($this->modelClass))) {
            throw new \RuntimeException(
                sprintf('Class "%s" does not exist or could not be found.', $this->modelClass)
            );
        }

        return new $this->modelClass($data);
    }

    /**
     * @param AbstractModel $genuineEntity
     * @param array $data
     * @return AbstractModel
     */
    public function copy(AbstractModel $genuineEntity, array $data = [])
    {
        if (!(class_exists($this->modelClass))) {
            throw new \RuntimeException(
                sprintf('Class "%s" does not exist or could not be found.', $this->modelClass)
            );
        }

        $aggregatedData = array_merge($genuineEntity->toArray(), $data);
        return $this->create($aggregatedData);
    }
}
