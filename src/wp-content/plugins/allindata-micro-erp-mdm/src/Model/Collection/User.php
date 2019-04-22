<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Collection;

use AllInData\MicroErp\Core\Model\AbstractCollection;
use AllInData\MicroErp\Mdm\Model\Role\ManagerRole;
use AllInData\MicroErp\Mdm\Model\Role\OwnerRole;
use AllInData\MicroErp\Mdm\Model\Role\UserRole;
use WP_User_Query;

/**
 * Class User
 * @package AllInData\MicroErp\Mdm\Model\Collection
 */
class User extends AbstractCollection
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $queryArgs
     * @return array
     */
    public function load($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET, array $queryArgs = []): array
    {
        $args = array_merge($this->getDefaultQueryArguments($limit, $offset), $queryArgs);
        $query = new WP_User_Query($args);

        $collectionIdSet = (array)$query->get_results();

        if (!is_array($collectionIdSet)) {
            return [];
        }

        $collectionItems = [];
        foreach ($collectionIdSet as $item) {
            $entity = $this->getResource()->loadById($item->ID);
            $collectionItems[] = $entity;
        }

        return $collectionItems;
    }

    /**
     * @param array $queryArgs
     * @return int
     */
    public function getTotalCount(array $queryArgs = []): int
    {
        $args = array_merge($this->getDefaultTotalsQueryArguments(), $queryArgs);
        $query = new WP_User_Query($args);
        return $query->get_total();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    protected function getDefaultQueryArguments($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET): array
    {
        return [
            'role__in' => [
                UserRole::ROLE_LEVEL,
                ManagerRole::ROLE_LEVEL,
                OwnerRole::ROLE_LEVEL
            ],
            'fields' => [
                'ID'
            ],
            'offset' => $offset,
            'number' => $limit
        ];
    }

    /**
     * @return array
     */
    protected function getDefaultTotalsQueryArguments(): array
    {
        return [
            'role__in' => [
                UserRole::ROLE_LEVEL,
                ManagerRole::ROLE_LEVEL,
                OwnerRole::ROLE_LEVEL
            ],
            'fields' => [
                'ID'
            ]
        ];
    }
}