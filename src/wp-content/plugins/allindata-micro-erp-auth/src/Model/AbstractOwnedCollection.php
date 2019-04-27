<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Model;

/**
 * Class AbstractOwnedCollection
 * @package AllInData\MicroErp\Auth\Model
 */
abstract class AbstractOwnedCollection extends \AllInData\MicroErp\Core\Model\AbstractOwnedCollection
{
    /**
     * @TODO Support user scope take over by leading user role or administrator
     * @return int[]
     */
    protected function getCurrentScopeUserIdSet(): array
    {
        return [get_current_user_id()];
    }
}
