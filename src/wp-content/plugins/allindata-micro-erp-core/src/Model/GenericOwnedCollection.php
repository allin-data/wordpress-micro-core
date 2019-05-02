<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

/**
 * Class GenericOwnedCollection
 * @package AllInData\MicroErp\Core\Model
 */
class GenericOwnedCollection extends AbstractOwnedCollection
{
    /**
     * @return int[]
     */
    protected function getCurrentScopeUserIdSet(): array
    {
        return apply_filters('micro_erp_query_current_scope_user_id', [get_current_user_id()]);
    }
}