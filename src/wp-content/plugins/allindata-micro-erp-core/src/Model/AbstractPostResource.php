<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

use AllInData\MicroErp\Core\Database\WordpressDatabase;
use RuntimeException;

/**
 * Class AbstractPostResource
 * @package AllInData\MicroErp\Core\Model
 */
abstract class AbstractPostResource extends AbstractResource
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
            ->setPostModified($entity->getPostModified() ?? date('Y-m-d H:i:s'))
            ->setPostModifiedGmt($entity->getPostModifiedGmt() ?? date('Y-m-d H:i:s'))
            ->setGuid($entity->getGuid() ?? get_the_guid($entity));
        return $entity;
    }
}
