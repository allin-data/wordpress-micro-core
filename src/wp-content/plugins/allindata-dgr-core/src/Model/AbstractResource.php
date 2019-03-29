<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\Model;

use AllInData\Dgr\Core\Database\WordpressDatabase;

/**
 * Class AbstractModel
 * @package AllInData\Dgr\Core\Model
 */
abstract class AbstractResource
{
    /*
     *
     */
    const ENTITY_NAME = '';

    /**
     * @var WordpressDatabase
     */
    private $database;
    /**
     * @var string
     */
    private $entityName;
    /**
     * @var AbstractFactory
     */
    private $modelFactory;
    /**
     * @var string
     */
    private $primaryKey;

    /**
     * AbstractResource constructor.
     * @param WordpressDatabase $database
     * @param string $entityName
     * @param AbstractFactory $modelFactory
     * @param string $primaryKey
     */
    public function __construct(
        WordpressDatabase $database,
        string $entityName,
        AbstractFactory $modelFactory,
        string $primaryKey = 'ID'
    ) {
        $this->database = $database;
        $this->entityName = $entityName;
        $this->modelFactory = $modelFactory;
        $this->primaryKey = $primaryKey;
    }


    /**
     * @return WordpressDatabase
     */
    public function getDatabase(): WordpressDatabase
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * @return AbstractFactory
     */
    public function getModelFactory(): AbstractFactory
    {
        return $this->modelFactory;
    }

    /**
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * @param mixed $id
     * @return AbstractModel
     */
    public function loadById($id): AbstractModel
    {
        $db = $this->database->getInstance();

        $queryEntity = $db->prepare(
            'SELECT * FROM %s WHERE post_type="%s" AND %s=%s',
            $db->posts,
            $this->entityName,
            $this->primaryKey,
            $id
        );

        /** @var array $entity */
        $entity = $db->get_row(
            $queryEntity,
            ARRAY_A
        );

        $queryEntityData = $db->prepare(
            'SELECT * FROM %s WHERE post_id=%s',
            $db->postmeta,
            $id
        );

        /** @var array $entityData */
        $entityData = $db->get_row(
            $queryEntityData,
            ARRAY_A
        );

        $data = array_merge($entity, $entityData);
        $entity = $this->modelFactory->create($data);

        return $entity;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    public function save(AbstractModel $entity)
    {
        if ($entity->get($this->primaryKey)) {
            $entity = $this->update($entity);
        } else {
            $entity = $this->insert($entity);
        }
        return $entity;
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function deleteById($id): bool
    {
        $db = $this->database->getInstance();

        $db->delete($db->postmeta, [
            'post_id' => $id
        ]);
        $db->delete($db->posts, [
            $this->primaryKey => $id,
            'post_type' => $this->entityName
        ]);

        return true;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function insert(AbstractModel $entity)
    {
        //@TODO implementation
        return $entity;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function update(AbstractModel $entity)
    {
        //@TODO implementation
        return $entity;
    }
}
