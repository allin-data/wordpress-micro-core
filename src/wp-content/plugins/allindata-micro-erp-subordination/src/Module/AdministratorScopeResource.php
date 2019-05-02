<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Subordination\Module;

use WP_User_Query;

/**
 * Class AdministratorScopeResource
 * @package AllInData\MicroErp\Subordination\Module
 */
class AdministratorScopeResource extends AbstractScopeResource
{
    /**
     * @inheritDoc
     */
    protected function doApplyCurrentUserScope(array $currentUserScopeIdSet = []): array
    {
        $query = new WP_User_Query(['fields' => 'ID']);
        return $query->get_results();
    }
}