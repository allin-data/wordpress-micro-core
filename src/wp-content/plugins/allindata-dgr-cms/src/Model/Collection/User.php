<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model\Collection;

use AllInData\Dgr\Core\Model\AbstractCollection;

/**
 * Class User
 * @package AllInData\Dgr\Cms\Model\Collection
 */
class User extends AbstractCollection
{
    /**
     * @TODO remove dummy
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function load($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET): array
    {
        /*
         * @TODO remove dummy
         */
        return [
            $this->getResource()->getModelFactory()->create([
                'ID' => 1,
                'firstName' => 'Foo',
                'lastName' => 'Bar'
            ]),
            $this->getResource()->getModelFactory()->create([
                'ID' => 2,
                'firstName' => 'Alice',
                'lastName' => 'Bob'
            ]),
            $this->getResource()->getModelFactory()->create([
                'ID' => 3,
                'firstName' => 'Max',
                'lastName' => 'Mustermann'
            ])
        ];
        /*
         * @TODO remove dummy
         */
    }
}