<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model\Resource;

use AllInData\Dgr\Core\Model\AbstractModel;
use AllInData\Dgr\Core\Model\AbstractResource;
use function sanitize_user;
use function wp_hash_password;

/**
 * Class User
 * @package AllInData\Dgr\Cms\Model\Resource
 */
class User extends AbstractResource
{
    const ENTITY_NAME = 'users';

    /**
     * @param mixed $id
     * @return AbstractModel
     */
    public function loadById($id): AbstractModel
    {
        $db = $this->getDatabase()->getInstance();

        $queryEntity = $db->prepare(
            'SELECT * FROM `%s` WHERE `ID`=%u',
            $db->users,
            $id
        );

        /** @var array $entity */
        $entity = $db->get_row(
            $queryEntity,
            ARRAY_A
        );

        $queryEntityData = $db->prepare(
            'SELECT * FROM `%s` WHERE `user_id`=%u',
            $db->usermeta,
            $id
        );

        /** @var array $entityData */
        $entityData = $db->get_row(
            $queryEntityData,
            ARRAY_A
        );

        $data = array_merge($entity, $entityData);
        $entity = $this->getModelFactory()->create($data);

        return $entity;
    }

    /**
     * @param mixed $id
     * @return bool
     */
    public function deleteById($id): bool
    {
        $db = $this->getDatabase()->getInstance();

        $db->delete($db->usermeta, [
            'user_id' => $id
        ]);
        $db->delete($db->users, [
            'ID' => $id
        ]);

        return true;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function insert(AbstractModel $entity)
    {
        $postData = $this->extractUserData($entity->toArray());
        $postData['user_pass'] = wp_hash_password($postData['user_pass']);
        $postData['user_login'] = sanitize_user($postData['user_login'], false);

        $db = $this->getDatabase()->getInstance();
        $db->insert(
            $db->users,
            $postData['data'],
            $postData['format']
        );
        $entityId = $db->insert_id;
        $entity->set($this->getPrimaryKey(), $entityId);

        $postMetaDataSet = $this->extractUserMetaData($entity->toArray());
        foreach ($postMetaDataSet as $postMetaData) {
            $db->delete($db->usermeta, [
                'user_id' => $postMetaData['data']['user_id'],
                'meta_key' => $postMetaData['data']['meta_key']
            ]);
            $db->insert(
                $db->usermeta,
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
        $postData = $this->extractUserData($entity->toArray());

        $db = $this->getDatabase()->getInstance();
        $db->update(
            $db->users,
            $postData['data'],
            [
                'ID' => $entity->get($this->getPrimaryKey())
            ],
            $postData['format']
        );

        $postMetaDataSet = $this->extractUserMetaData($entity->toArray());
        foreach ($postMetaDataSet as $postMetaData) {
            $db->delete($db->usermeta, [
                'user_id' => $postMetaData['data']['user_id'],
                'meta_key' => $postMetaData['data']['meta_key']
            ]);
            $db->insert(
                $db->usermeta,
                $postMetaData['data'],
                $postMetaData['format']
            );
        }

        return $entity;
    }
    /**
     * @param array $entityData
     * @return array
     */
    private function extractUserData(array $entityData): array
    {
        $primaryKeyValue = $entityData[$this->getPrimaryKey()] ?: null;

        $postEntityDataKeySet = $this->getUserEntityDataKeySet();
        $data = array_filter($entityData, function ($item) use ($postEntityDataKeySet) {
            return isset($postEntityDataKeySet, $item);
        }, ARRAY_FILTER_USE_KEY);
        $data['ID'] = $primaryKeyValue;

        $mappingSet = [
            'data' => $data,
            'format' => $this->getFormatSet($data)
        ];

        return $mappingSet;
    }

    /**
     * @param array $entityData
     * @return array
     */
    private function extractUserMetaData(array $entityData): array
    {
        $primaryKeyValue = $entityData[$this->getPrimaryKey()] ?: null;

        $postEntityDataKeySet = $this->getUserEntityDataKeySet();
        $data = array_filter($entityData, function ($item) use ($postEntityDataKeySet) {
            return !isset($postEntityDataKeySet, $item);
        }, ARRAY_FILTER_USE_KEY);

        $mappingSet = [];
        foreach ($data as $key => $value) {
            $set = [
                'user_id' => $primaryKeyValue,
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
    private function getUserEntityDataKeySet(): array
    {
        return [
            'ID',
            'user_login',
            'user_pass',
            'user_nicename',
            'user_email',
            'user_url',
            'user_registered',
            'user_activation_key',
            'user_status',
            'display_name'
        ];
    }
}