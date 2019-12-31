<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

use WP_Query;

/**
 * Class AbstractOwnedCollection
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractOwnedCollection extends AbstractCollection
{
    /**
     * Reverts the author filter and fetches all entities, matching to the remaining query arguments
     * @param int $limit
     * @param int $offset
     * @param array $queryArgs
     * @return AbstractModel[]
     */
    public function loadBypassOwnership(
        $limit = self::DEFAULT_LIMIT,
        $offset = self::DEFAULT_OFFSET,
        array $queryArgs = []
    ): array {
        $args = array_merge($queryArgs, $this->getDefaultQueryArguments($limit, $offset));
        unset($args['author__in']);
        $query = new WP_Query($args);

        $collectionIdSet = (array)$query->get_posts();

        if (!is_array($collectionIdSet)) {
            return [];
        }

        $collectionItems = [];
        foreach ($collectionIdSet as $id) {
            /** @var AbstractOwnedResource $resource */
            $resource = $this->getResource();
            $entity = $resource->loadBypassOwnership($id);
            $collectionItems[] = $entity;
        }

        return $collectionItems;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    protected function getDefaultQueryArguments($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET): array
    {
        $queryArgs = parent::getDefaultQueryArguments($limit, $offset);

        // apply current scope user id as a filter
        $queryArgs = array_merge($queryArgs, [
            'author__in' => $this->getCurrentScopeUserIdSet()
        ]);

        return $queryArgs;
    }

    /**
     * @return array
     */
    protected function getDefaultTotalsQueryArguments(): array
    {
        $queryArgs = parent::getDefaultTotalsQueryArguments();

        // apply current scope user id as a filter
        $queryArgs = array_merge($queryArgs, [
            'author__in' => $this->getCurrentScopeUserIdSet()
        ]);

        return $queryArgs;
    }

    /**
     * @return int[]
     */
    protected function getCurrentScopeUserIdSet(): array
    {
        return apply_filters('micro_core_query_current_scope_user_id', [get_current_user_id()]);
    }
}
