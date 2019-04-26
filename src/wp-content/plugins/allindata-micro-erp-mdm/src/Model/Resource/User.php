<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Resource;

use AllInData\MicroErp\Core\Helper\MethodUtil;
use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Core\Model\AbstractResource;
use function sanitize_user;
use function wp_hash_password;

/**
 * Class User
 * @package AllInData\MicroErp\Mdm\Model\Resource
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
            'SELECT * FROM `'.$db->users.'` WHERE `ID`=%d',
            $id
        );

        /** @var array $entity */
        $entity = $db->get_row(
            $queryEntity,
            ARRAY_A
        );

        $queryEntityData = $db->prepare(
            'SELECT * FROM `'.$db->usermeta.'` WHERE `user_id`=%d',
            $id
        );

        /** @var array $entityData */
        $entityData = $db->get_results(
            $queryEntityData,
            ARRAY_A
        );

        $mappedEntity = $this->mapUserData($entity);
        $mappedEntityData = $this->mapUserMetaData($entityData);
        $data = array_merge($mappedEntity, $mappedEntityData);
        $entity = $this->getModelFactory()->create($data);
;
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
        $userData = $this->extractUserData($entity->toArray());
        $userData['user_pass'] = wp_hash_password($userData['user_pass']);
        $userData['user_login'] = sanitize_user($userData['user_login'], false);

        $db = $this->getDatabase()->getInstance();
        $db->insert(
            $db->users,
            $userData['data'],
            $userData['format']
        );
        $entityId = $db->insert_id;
        $entity->set($this->getPrimaryKey(), $entityId);

        $userMetaDataSet = $this->extractUserMetaData($entity->toArray());
        foreach ($userMetaDataSet as $userMetaData) {
            $db->delete($db->usermeta, [
                'user_id' => $userMetaData['data']['user_id'],
                'meta_key' => $userMetaData['data']['meta_key']
            ]);
            $db->insert(
                $db->usermeta,
                $userMetaData['data'],
                $userMetaData['format']
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
        $userData = $this->extractUserData($entity->toArray());

        $db = $this->getDatabase()->getInstance();
        $db->update(
            $db->users,
            $userData['data'],
            [
                'ID' => $entity->get($this->getPrimaryKey())
            ],
            $userData['format']
        );

        $userMetaDataSet = $this->extractUserMetaData($entity->toArray());
        foreach ($userMetaDataSet as $userMetaData) {
            $db->delete($db->usermeta, [
                'user_id' => $userMetaData['data']['user_id'],
                'meta_key' => $userMetaData['data']['meta_key']
            ]);
            $db->insert(
                $db->usermeta,
                $userMetaData['data'],
                $userMetaData['format']
            );
        }

        return $entity;
    }

    /**
     * @param array $entityData
     * @return array
     */
    private function mapUserData(array $entityData): array
    {
        $primaryKeyValue = $entityData['ID'] ?: null;

        $postEntityDataKeySet = $this->getUserEntityDataKeySet();
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
    private function mapUserMetaData(array $entityData): array
    {
        $postEntityDataKeySet = $this->getUserEntityDataKeySet();
        $filteredDataSet = [];
        foreach($entityData as $attributeSet) {
            $itemKey = $attributeSet['meta_key'];
            $itemValue = $attributeSet['meta_value'];
            if (in_array($itemKey, $postEntityDataKeySet)) {
                continue;
            }
            $key = MethodUtil::canonicalizeAttributeName($itemKey);

            if (is_serialized($itemValue)) {
                $itemValue = unserialize($itemValue);
            }

            $filteredDataSet[$key] = $itemValue;
        }
        return $filteredDataSet;
    }

    /**
     * @param array $entityData
     * @return array
     */
    private function extractUserData(array $entityData): array
    {
        $primaryKeyValue = $entityData[$this->getPrimaryKey()] ?: null;

        $postEntityDataKeySet = $this->getUserEntityDataKeySet();
        $filteredDataSet = [];
        foreach ($entityData as $itemKey => $itemValue) {
            $key = MethodUtil::decanonicalizeAttributeName($itemKey);
            if (!in_array($key, $postEntityDataKeySet)) {
                continue;
            }
            $filteredDataSet[$key] = $itemValue;
        }
        $filteredDataSet['ID'] = $primaryKeyValue;

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
    private function extractUserMetaData(array $entityData): array
    {
        $primaryKeyValue = $entityData[$this->getPrimaryKey()] ?: null;

        $postEntityDataKeySet = $this->getUserEntityDataKeySet();
        $filteredDataSet = [];
        foreach ($entityData as $itemKey => $itemValue) {
            $key = MethodUtil::decanonicalizeAttributeName($itemKey);
            if (in_array($key, $postEntityDataKeySet)) {
                continue;
            }
            $filteredDataSet[$key] = $itemValue;
        }

        $mappingSet = [];
        foreach ($filteredDataSet as $key => $value) {
            if (in_array($key, [
                $this->getPrimaryKey(),
                'umeta_id'
            ])) {
                continue;
            }

            if (!is_scalar($value)) {
                $value = serialize($value);
            }

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