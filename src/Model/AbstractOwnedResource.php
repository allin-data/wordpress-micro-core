<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

/**
 * Class AbstractOwnedResource
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractOwnedResource extends AbstractResource
{
    /**
     * @param mixed $id
     * @return AbstractModel
     */
    public function loadBypassOwnership($id): AbstractModel
    {
        $db = $this->getDatabase()->getInstance();

        $queryEntity = $db->prepare(
            'SELECT * FROM `'.$db->posts.'` WHERE `post_type`=%s AND `ID`=%d',
            $this->getEntityName(),
            $id
        );

        /** @var array $entity */
        $entity = $db->get_row(
            $queryEntity,
            ARRAY_A
        );
        if (empty($entity)) {
            return $this->getModelFactory()->create();
        }

        $entityData = [];
        if (!empty($entity)) {
            $queryEntityData = $db->prepare(
                'SELECT * FROM `'.$db->postmeta.'` WHERE `post_id`=%d',
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
        $entity = $this->getModelFactory()->create($data);

        return $entity;
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function beforeInsert(AbstractModel $entity): AbstractModel
    {
        if (!($entity instanceof AbstractPostModel)) {
            return $entity;
        }

        return $this->presetData($entity);
    }

    /**
     * @param AbstractModel $entity
     * @return AbstractModel
     */
    protected function beforeUpdate(AbstractModel $entity): AbstractModel
    {
        if (!($entity instanceof AbstractPostModel)) {
            return $entity;
        }

        return $this->presetData($entity);
    }

    /**
     * @inheritDoc
     */
    protected function getAdditionalLoadWhereEntity(): string
    {
        return sprintf(
            'AND `post_author` IN (%s)',
            implode(',', $this->getCurrentScopeUserIdSet())
        );
    }

    /**
     * @param AbstractModel $entity
     * @return bool
     */
    protected function validateEntity(AbstractModel $entity): bool
    {
        if (!($entity instanceof AbstractPostModel)) {
            return true;
        }

        if (!is_user_logged_in()) {
            return false;
        }

        if ($entity->getPostAuthor() !== get_current_user_id() &&
            !current_user_can('administrator')) {
            return false;
        }

        return true;
    }

    /**
     * @return int[]
     */
    protected function getCurrentScopeUserIdSet(): array
    {
        return apply_filters('micro_core_query_current_scope_user_id', [get_current_user_id()]);
    }

    /**
     * @param AbstractPostModel $entity
     * @return AbstractPostModel
     */
    private function presetData(AbstractPostModel $entity)
    {
        $entity
            ->setPostType($entity->getPostType() ?? static::ENTITY_NAME)
            ->setPostAuthor($entity->getPostAuthor() ?? get_current_user_id())
            ->setPostTitle($entity->getPostTitle() ?? '')
            ->setPostName($entity->getPostName() ?? sanitize_title($entity->getPostTitle()))
            ->setPostContent($entity->getPostContent() ?? '')
            ->setPostContentFiltered($entity->getPostContentFiltered() ?? '')
            ->setPostExcerpt($entity->getPostExcerpt() ?? '')
            ->setPostStatus($entity->getPostStatus() ?? 'publish')
            ->setCommentStatus($entity->getCommentStatus() ?? 'closed')
            ->setPingStatus($entity->getPingStatus() ?? 'closed')
            ->setPostPassword($entity->getPostPassword() ?? '')
            ->setPostParent($entity->getPostParent() ?? 0)
            ->setMenuOrder($entity->getMenuOrder() ?? 0)
            ->setCommentCount($entity->getCommentCount() ?? 0)
            ->setToPing($entity->getToPing() ?? '')
            ->setPinged($entity->getPinged() ?? '')
            ->setPostMimeType($entity->getPostMimeType() ?? '')
            ->setPostDate($entity->getPostDate() ?? date('Y-m-d H:i:s'))
            ->setPostDateGmt($entity->getPostDateGmt() ?? date('Y-m-d H:i:s'))
            ->setPostModified(date('Y-m-d H:i:s'))
            ->setPostModifiedGmt(date('Y-m-d H:i:s'))
            ->setGuid($entity->getGuid() ?? get_the_guid($entity));
        return $entity;
    }
}
