<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

/**
 * Class AbstractPostResource
 * @package AllInData\MicroErp\Core\Model
 */
abstract class AbstractPostResource extends AbstractOwnedResource
{
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
     * @param AbstractPostModel $entity
     * @return AbstractPostModel
     */
    private function presetData(AbstractPostModel $entity)
    {
        $entity
            ->setPostType($entity->getPostType() ?? static::ENTITY_NAME)
            ->setPostAuthor($entity->getPostAuthor() ?? $this->getCurrentScopeUserId())
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
