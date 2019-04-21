<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

/**
 * Class AbstractOwnedCollection
 * @package AllInData\MicroErp\Core\Model
 */
abstract class AbstractOwnedCollection extends AbstractCollection
{
    /**
     * @param int $limit
     * @param int $offset
     * @param array $queryArgs
     * @return AbstractModel[]
     */
    public function load($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET, $queryArgs = []): array
    {
        // apply current scope user id as a filter
        $queryArgs = array_merge($queryArgs, [
            'author' => $this->getCurrentScopeUserId()
        ]);

        return parent::load($limit, $offset, $queryArgs);
    }

    /**
     * @return int
     */
    protected function getCurrentScopeUserId(): int
    {
        return get_current_user_id();
    }
}
