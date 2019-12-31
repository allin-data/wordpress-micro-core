<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

use Elementor\Core\Common\Modules\Finder\Base_Category as Entity;

/**
 * Class AbstractElementorFactory
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractElementorFactory
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
     * @return Entity
     */
    public function create(array $data = []): Entity
    {
        if (!(class_exists($this->modelClass))) {
            throw new \RuntimeException(
                sprintf('Class "%s" does not exist or could not be found.', $this->modelClass)
            );
        }

        return new $this->modelClass($data);
    }
}
