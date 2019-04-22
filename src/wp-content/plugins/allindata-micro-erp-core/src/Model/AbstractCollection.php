<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

use WP_Query;

/**
 * Class AbstractCollection
 * @package AllInData\MicroErp\Core\Model
 */
abstract class AbstractCollection
{
    const DEFAULT_LIMIT = 20;
    const DEFAULT_OFFSET = 0;

    /**
     * @var AbstractResource
     */
    private $resource;

    /**
     * AbstractCollection constructor.
     * @param AbstractResource $resource
     */
    public function __construct(AbstractResource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return AbstractResource
     */
    public function getResource(): AbstractResource
    {
        return $this->resource;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @param array $queryArgs
     * @return AbstractModel[]
     */
    public function load($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET, array $queryArgs = []): array
    {
        $args = array_merge($queryArgs, $this->getDefaultQueryArguments($limit, $offset));
        $query = new WP_Query($args);

        $collectionIdSet = (array)$query->get_posts();

        if (!is_array($collectionIdSet)) {
            return [];
        }

        $collectionItems = [];
        foreach ($collectionIdSet as $id) {
            $entity = $this->resource->loadById($id);
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
        $args = array_merge($queryArgs, $this->getDefaultTotalsQueryArguments());
        $query = new WP_Query($args);
        return $query->post_count;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array
     */
    protected function getDefaultQueryArguments($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET): array
    {
        return [
            'fields' => 'ids',
            'post_type' => $this->resource->getEntityName(),
            'post_status' => 'publish',
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
            'fields' => 'ids',
            'post_type' => $this->resource->getEntityName(),
            'post_status' => 'publish',
            'cache_results'  => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false
        ];
    }

}
