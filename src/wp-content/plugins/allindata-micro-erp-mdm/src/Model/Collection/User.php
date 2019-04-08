<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Collection;

use AllInData\MicroErp\Mdm\Model\UserRole;
use AllInData\MicroErp\Core\Model\AbstractCollection;

/**
 * Class User
 * @package AllInData\MicroErp\Mdm\Model\Collection
 */
class User extends AbstractCollection
{
    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function load($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET): array
    {
        $query = new \WP_User_Query([
            'role' => UserRole::ROLE_LEVEL_USER_DEFAULT,
            'fields' => [
                'ID'
            ],
            'offset' => $offset,
            'number' => $limit
        ]);

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
     * @return int
     */
    public function getTotalCount(): int
    {
        $query = new \WP_User_Query([
            'role' => UserRole::ROLE_LEVEL_USER_DEFAULT,
            'fields' => [
                'ID'
            ]
        ]);

        return $query->get_total();
    }
}