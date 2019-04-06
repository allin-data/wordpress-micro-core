<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model\Factory;

use AllInData\Dgr\Cms\Model\ElementorCmsCategory as Entity;

/**
 * Class ElementorCmsCategory
 * @package AllInData\Dgr\Cms\Model\Factory
 */
class ElementorCmsCategory
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