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
     * References
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
        string $primaryKey = 'id'
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
            'SELECT * FROM `'.$db->posts.'` WHERE `post_type`=%s AND `ID`=%d',
            $this->entityName,
            $id
        );

        /** @var array $entity */
        $entity = $db->get_row(
            $queryEntity,
            ARRAY_A
        );

        $queryEntityData = $db->prepare(
            'SELECT * FROM `'.$db->postmeta.'` WHERE `post_id`=%d',
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
            'ID' => $id,
            'post_type' => $this->entityName
        ]);

        return true;
    }

    /**
     * Maps camel_case to CamelCase
     * @param string $attributeName
     * @return string
     */
    public function canonicalizeAttributeName(string $attributeName): string
    {
        $attributeNameParts = explode(' ', ucwords(str_replace(['-','_'], ' ', $attributeName)));
        foreach ($attributeNameParts as $idx => $part) {
            $attributeNameParts[$idx] = ucfirst(strtolower($part));
        }

        return implode('', $attributeNameParts);
    }

    /**
     * Maps CamelCase to camel_case
     * @param string $attributeName
     * @return string
     */
    public function decanonicalizeAttributeName(string $attributeName): string
    {
        $attributeNameParts = preg_split('/(?=[A-Z])/', $attributeName);
        foreach ($attributeNameParts as $idx => $part) {
            $attributeNameParts[$idx] = strtolower($part);
        }

        return implode('_', $attributeNameParts);
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function insert(AbstractModel $entity)
    {
        $postData = $this->extractPostData($entity->toArray());

        $db = $this->database->getInstance();
        $db->insert(
            $db->posts,
            $postData['data'],
            $postData['format']
        );
        $entityId = $db->insert_id;
        $entity->set($this->primaryKey, $entityId);

        $postMetaDataSet = $this->extractPostMetaData($entity->toArray());
        foreach ($postMetaDataSet as $postMetaData) {
            $db->delete($db->postmeta, [
                'post_id' => $postMetaData['data']['post_id'],
                'meta_key' => $postMetaData['data']['meta_key']
            ]);
            $db->insert(
                $db->postmeta,
                $postMetaData['data'],
                $postMetaData['format']
            );
        }

        return $entity;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function update(AbstractModel $entity)
    {
        $postData = $this->extractPostData($entity->toArray());
        $db = $this->database->getInstance();
        $db->update(
            $db->posts,
            $postData['data'],
            [
                'ID' => $entity->get($this->primaryKey),
                'post_type' => $this->entityName
            ],
            $postData['format']
        );

        $postMetaDataSet = $this->extractPostMetaData($entity->toArray());

        foreach ($postMetaDataSet as $postMetaData) {
            $db->delete($db->postmeta, [
                'post_id' => $postMetaData['data']['post_id'],
                'meta_key' => $postMetaData['data']['meta_key']
            ]);
            $db->insert(
                $db->postmeta,
                $postMetaData['data'],
                $postMetaData['format']
            );
        }

        return $entity;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function getFormatSet(array $data): array
    {
        return [];
    }

    /**
     * @param array $entityData
     * @return array
     */
    private function extractPostData(array $entityData): array
    {
        $primaryKeyValue = $entityData[$this->primaryKey] ?: null;

        $postEntityDataKeySet = $this->getPostEntityDataKeySet();
        $filteredDataSet = [];
        foreach ($entityData as $itemKey => $itemValue) {
            $key = $this->decanonicalizeAttributeName($itemKey);
            if (!in_array($key, $postEntityDataKeySet)) {
                continue;
            }
            $filteredDataSet[$key] = $itemValue;
        }

        unset($filteredDataSet[$this->primaryKey]);
        $filteredDataSet['ID'] = $primaryKeyValue;
        $filteredDataSet['post_type'] = $this->entityName;

        $mappingSet = [
            'data' => $filteredDataSet,
            'format' => $this->getFormatSet($filteredDataSet)
        ];

        return $mappingSet;
    }

    /**
     * @param array $entityData
     * @return array
     */
    private function extractPostMetaData(array $entityData): array
    {
        $primaryKeyValue = $entityData[$this->primaryKey] ?: null;

        $postEntityDataKeySet = $this->getPostEntityDataKeySet();
        $filteredDataSet = [];
        foreach ($entityData as $itemKey => $itemValue) {
            $key = $this->decanonicalizeAttributeName($itemKey);
            if (in_array($key, $postEntityDataKeySet)) {
                continue;
            }
            $filteredDataSet[$key] = $itemValue;
        }

        $mappingSet = [];
        foreach ($filteredDataSet as $key => $value) {
            if (in_array($key, [
                $this->primaryKey,
                'meta_id'
            ])) {
                continue;
            }

            $set = [
                'post_id' => $primaryKeyValue,
                'meta_key' => $key,
                'meta_value' => $value
            ];
            $mappingSet[] = [
                'data' => $set,
                'format' => $this->getFormatSet($set)
            ];
        }
        return $mappingSet;
    }

    /**
     * @return array
     */
    private function getPostEntityDataKeySet(): array
    {
        return [
            'ID',
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_title',
            'post_excerpt',
            'post_status',
            'comment_status',
            'ping_status',
            'post_password',
            'post_name',
            'to_ping',
            'pinged',
            'post_modified',
            'post_modified_gmt',
            'post_content_filtered',
            'post_parent',
            'guid',
            'menu_order',
            'post_type',
            'post_mime_type',
            'comment_count'
        ];
    }
}
