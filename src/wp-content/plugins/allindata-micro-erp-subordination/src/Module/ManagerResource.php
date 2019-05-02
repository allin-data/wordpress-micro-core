<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Subordination\Module;

use AllInData\MicroErp\Core\Module\PluginModuleInterface;

/**
 * Class ManagerResource
 * @package AllInData\MicroErp\Subordination\Module
 */
class ManagerResource implements PluginModuleInterface
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        add_filter('micro_erp_query_current_scope_user_id', [$this, 'applyCurrentUserScopeIdSet'], 10, 1);
    }

    /**
     * @param array $currentUserScopeIdSet
     * @return array
     */
    public function applyCurrentUserScopeIdSet(array $currentUserScopeIdSet = []): array
    {
        return $currentUserScopeIdSet;
    }
}