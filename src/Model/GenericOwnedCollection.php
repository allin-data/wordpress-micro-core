<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

/**
 * Class GenericOwnedCollection
 * @package AllInData\Micro\Core\Model
 */
class GenericOwnedCollection extends AbstractOwnedCollection
{
    /**
     * @return int[]
     */
    protected function getCurrentScopeUserIdSet(): array
    {
        return apply_filters('micro_core_query_current_scope_user_id', [get_current_user_id()]);
    }
}