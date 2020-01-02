<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

use AllInData\Micro\Core\Database\WordpressDatabase;
use AllInData\Micro\Core\Helper\MethodUtil;
use RuntimeException;

/**
 * Class AbstractModel
 * @package AllInData\Micro\Core\Model
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
    protected $database;
    /**
     * @var string
     */
    protected $entityName;
    /**
     * @var AbstractFactory
     */
    protected $modelFactory;
    /**
     * @var string
     */
    protected $primaryKey;

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
        if (20 < strlen($entityName)) {
            throw new \InvalidArgumentException('The provided entity name must not be longer then 20 characters.');
        }

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
            'SELECT * FROM `' . $db->posts . '` WHERE `post_type`=%s AND `ID`=%d ' . $this->getAdditionalLoadWhereEntity(),
            $this->entityName,
            $id
        );

        /** @var array $entity */
        $entity = $db->get_row(
            $queryEntity,
            ARRAY_A
        );
        if (empty($entity)) {
            return $this->modelFactory->create();
        }

        $entityData = [];
        if (!empty($entity)) {
            $queryEntityData = $db->prepare(
                'SELECT * FROM `' . $db->postmeta . '` WHERE `post_id`=%d ' . $this->getAdditionalLoadWhereEntityData(),
                $id
            );

            /** @var array $entityData */
            $entityData = $db->get_results(
                $queryEntityData,
                ARRAY_A
            );
        }

        $mappedEntity = $this->mapPostData($entity);
        $mappedEntityData = $this->mapPostMetaData($entityData);
        $data = array_merge($mappedEntity, $mappedEntityData);
        $entity = $this->modelFactory->create($data);

        return $entity;
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function existsById($id): bool
    {
        $db = $this->database->getInstance();

        $query = $db->prepare(
            'SELECT * FROM `' . $db->posts . '` WHERE `post_type`=%s AND `ID`=%d ' . $this->getAdditionalLoadWhereEntity(),
            $this->entityName,
            $id
        );

        /** @var array $entity */
        $entity = $db->get_row(
            $query,
            ARRAY_A
        );

        if (empty($entity) || !isset($entity['ID'])) {
            return false;
        }

        return true;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    public function save(AbstractModel $entity)
    {
        if (!empty($entity->get($this->primaryKey))) {
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
     * @param array $entityData
     * @return array
     */
    public function mapPostData(array $entityData): array
    {
        $primaryKeyValue = $entityData['ID'] ?: null;

        $postEntityDataKeySet = $this->getPostEntityDataKeySet();
        $filteredDataSet = [];
        foreach ($entityData as $itemKey => $itemValue) {
            if (!in_array($itemKey, $postEntityDataKeySet)) {
                continue;
            }
            $key = MethodUtil::canonicalizeAttributeName($itemKey);
            $filteredDataSet[$key] = $itemValue;
        }
        $filteredDataSet[$this->getPrimaryKey()] = $primaryKeyValue;

        return $filteredDataSet;
    }

    /**
     * @param array $entityData
     * @return array
     */
    public function mapPostMetaData(array $entityData): array
    {
        $postEntityDataKeySet = $this->getPostEntityDataKeySet();
        $filteredDataSet = [];
        foreach ($entityData as $attributeSet) {
            $itemKey = $attributeSet['meta_key'] ?? null;
            $itemValue = $attributeSet['meta_value'] ?? null;
            if (!$itemKey ||
                !$itemValue ||
                in_array($itemKey, $postEntityDataKeySet)) {
                continue;
            }
            /** @var string $itemKey */
            $key = MethodUtil::canonicalizeAttributeName((string)$itemKey);

            if (is_serialized($itemValue)) {
                $itemValue = unserialize($itemValue);
            }

            $filteredDataSet[$key] = $itemValue;
        }
        return $filteredDataSet;
    }

    /**
     * @return string
     */
    protected function getAdditionalLoadWhereEntity(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function getAdditionalLoadWhereEntityData(): string
    {
        return '';
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function insert(AbstractModel $entity)
    {
        $entity = $this->beforeInsert($entity);
        if (!$this->validateEntity($entity)) {
            throw new RuntimeException('Could not process entity');
        }
        $postData = $this->extractPostData($entity->toArray());

        $db = $this->database->getInstance();
        $db->insert(
            $db->posts,
            $postData['data'],
            $postData['format']
        );
        $entityId = $db->insert_id;
        if (!$entityId) {
            throw new RuntimeException('Could not persist entity');
        }
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
        $entity = $this->beforeUpdate($entity);
        if (!$this->validateEntity($entity)) {
            throw new RuntimeException('Could not process entity');
        }
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
        $castSet = [];
        foreach ($data as $value) {
            $castType = '%s';
            if (is_numeric($value) && intval($value) == $value) {
                $castType = '%d';
            } elseif (is_numeric($value) && floatval($value) == $value) {
                $castType = '%f';
            }
            if (is_bool($value)) {
                $castType = '%d';
            }
            $castSet[] = $castType;
        }
        return $castSet;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function beforeInsert(AbstractModel $entity): AbstractModel
    {
        return $entity;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function beforeUpdate(AbstractModel $entity): AbstractModel
    {
        return $entity;
    }

    /**
     * @param AbstractModel $entity
     * @return bool
     */
    protected function validateEntity(AbstractModel $entity): bool
    {
        return true;
    }

    /**
     * @param array $entityData
     * @return array
     */
    private function extractPostData(array $entityData): array
    {
        $primaryKeyValue = $entityData[$this->primaryKey] ?? null;

        $postEntityDataKeySet = $this->getPostEntityDataKeySet();
        $filteredDataSet = [];
        foreach ($entityData as $itemKey => $itemValue) {
            $key = MethodUtil::decanonicalizeAttributeName($itemKey);
            if (!in_array($key, $postEntityDataKeySet) ||
                is_null($itemValue)) {
                continue;
            }
            $filteredDataSet[$key] = $itemValue;
        }

        unset($filteredDataSet[$this->primaryKey]);
        if (!is_null($primaryKeyValue) && 0 !== $primaryKeyValue) {
            $filteredDataSet['ID'] = $primaryKeyValue;
        }
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
        $primaryKeyValue = $entityData[$this->primaryKey] ?? null;

        $postEntityDataKeySet = $this->getPostEntityDataKeySet();
        $filteredDataSet = [];
        foreach ($entityData as $itemKey => $itemValue) {
            $key = MethodUtil::decanonicalizeAttributeName($itemKey);
            if (in_array($key, $postEntityDataKeySet) ||
                is_null($itemValue)) {
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

            if (!is_scalar($value)) {
                $value = serialize($value);
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
