<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\Model;

/**
 * Class AbstractCollection
 * @package AllInData\Dgr\Core\Model
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
     * @return AbstractModel[]
     */
    public function load($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET): array
    {
        $db = $this->resource->getDatabase()->getInstance();

        $queryCollection = $db->prepare(
            'SELECT ID FROM `'.$db->posts.'` WHERE `post_type`=%s LIMIT %d OFFSET %d',
            $this->resource->getEntityName(),
            $limit,
            $offset
        );

        /** @var array $entity */
        $collectionIdSet = $db->get_results(
            $queryCollection,
            ARRAY_A
        );

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
     * @return int
     */
    public function getTotalCount(): int
    {
        $db = $this->resource->getDatabase()->getInstance();

        $queryCollection = $db->prepare(
            'SELECT ID FROM `'.$db->posts.'` WHERE `post_type`=%s',
            $this->resource->getEntityName()
        );

        /** @var array $entity */
        $collectionIdSet = $db->get_results(
            $queryCollection,
            ARRAY_A
        );

        if (!is_array($collectionIdSet)) {
            return 0;
        }

        return count($collectionIdSet);
    }
}
