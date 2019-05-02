<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Subordination\Module;

/**
 * Class ManagerScopeResource
 * @package AllInData\MicroErp\Subordination\Module
 */
class ManagerScopeResource extends AbstractScopeResource
{
    /**
     * @inheritDoc
     */
    protected function doApplyCurrentUserScope(array $currentUserScopeIdSet = []): array
    {
        // TODO: Implement doApplyCurrentUserScope() method.
        return $currentUserScopeIdSet;
    }
}