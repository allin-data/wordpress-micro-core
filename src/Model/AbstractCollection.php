<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

use AllInData\Micro\Core\Helper\MethodUtil;
use InvalidArgumentException;
use WP_Query;

/**
 * Class AbstractCollection
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractCollection
{
    const NO_LIMIT = 0;
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
     * @param array $args
     * @return AbstractModel[]
     */
    public function loadByQuery(array $args = []): array
    {
        $db = $this->getResource()->getDatabase()->getInstance();

        $query = $this->getQuery();
        $queryEntityData = $this->getPreparedQuery($query, $args);

        /** @var array $entityData */
        $entityDataSet = $db->get_results(
            $queryEntityData,
            ARRAY_A
        );

        $items = [];
        foreach ($entityDataSet as $entityData) {
            $mappedEntity = $this->getResource()->mapPostData($entityData);
            $mappedEntityData = $this->getResource()->mapPostMetaData($entityData);
            $data = array_merge($mappedEntity, $mappedEntityData);
            $items[] = $this->getResource()->getModelFactory()->create($data);
        }

        return $items;
    }

    /**
     * @return string
     */
    protected function getQuery(): string
    {
        $db = $this->getResource()->getDatabase()->getInstance();
        return 'SELECT * FROM `'.$db->postmeta.'` AS pm ' .
            'LEFT JOIN `'.$db->posts.'` AS p ON p.ID = pm.post_id ' .
            'WHERE 1=1';
    }

    /**
     * @param string $rawQueryString
     * @param array $args
     * @return string
     */
    protected function getPreparedQuery(string $rawQueryString, array $args): string
    {
        $db = $this->getResource()->getDatabase()->getInstance();
        return $db->prepare(
            $rawQueryString,
            []
        );
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
        if (static::NO_LIMIT === $limit) {
            return [
                'fields' => 'ids',
                'post_type' => $this->resource->getEntityName(),
                'post_status' => 'publish',
                'nopaging' => true,
                'posts_per_page' => -1
            ];
        }

        return [
            'fields' => 'ids',
            'post_type' => $this->resource->getEntityName(),
            'post_status' => 'publish',
            'offset' => $offset,
            'posts_per_page' => $limit
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
            'update_post_term_cache' => false,
            'nopaging' => true,
            'posts_per_page' => -1
        ];
    }

}
