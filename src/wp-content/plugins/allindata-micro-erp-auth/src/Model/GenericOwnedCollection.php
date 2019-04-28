<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Model;

use AllInData\MicroErp\Core\Model\GenericCollection;

/**
 * Class GenericOwnedCollection
 * @package AllInData\MicroErp\Auth\Model
 */
class GenericOwnedCollection extends GenericCollection
{
    /**
     * @TODO Support user scope take over by leading user role or administrator
     * @return int[]
     */
    protected function getCurrentScopeUserIdSet(): array
    {
        return apply_filters('micro_erp_query_current_scope_user_id', [get_current_user_id()]);
    }
}