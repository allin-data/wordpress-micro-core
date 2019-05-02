<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Subordination\Module;

use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Mdm\Model\Role\RoleInterface;

/**
 * Class AbstractScopeResource
 * @package AllInData\MicroErp\Subordination\Module
 */
abstract class AbstractScopeResource implements PluginModuleInterface
{
    /**
     * @var RoleInterface
     */
    private $role;

    /**
     * AbstractScopeResource constructor.
     * @param RoleInterface $role
     */
    public function __construct(RoleInterface $role)
    {
        $this->role = $role;
    }

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
        if (!$this->isApplicable()) {
            return $currentUserScopeIdSet;
        }

        return $this->doApplyCurrentUserScope($currentUserScopeIdSet);
    }

    /**
     * @param array $currentUserScopeIdSet
     * @return array
     */
    abstract protected function doApplyCurrentUserScope(array $currentUserScopeIdSet = []): array;

    /**
     * @return bool
     */
    protected function isApplicable(): bool
    {
        $user = wp_get_current_user();
        if ($user->has_cap($this->role->getRoleLevel())) {
            return true;
        }

        return false;
    }
}