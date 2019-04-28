<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

use AllInData\MicroErp\Core\Database\WordpressDatabase;
use RuntimeException;

/**
 * Class AbstractModel
 * @package AllInData\MicroErp\Core\Model
 */
abstract class AbstractOwnedResource extends AbstractResource
{
    /**
     * @inheritDoc
     */
    protected function getAdditionalLoadWhereEntity(): string
    {
        return sprintf(
            'AND `post_author` IN (%s)',
            implode(',', $this->getCurrentScopeUserId())
        );
    }

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
     * @return int[]
     */
    protected function getCurrentScopeUserId(): array
    {
        return apply_filters('micro_erp_query_current_scope_user_id', [get_current_user_id()]);
    }
}
