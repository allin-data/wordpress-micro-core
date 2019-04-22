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
            'AND `post_author`=%s',
            $this->getCurrentScopeUserId()
        );
    }

    /**
     * @return int
     */
    protected function getCurrentScopeUserId(): int
    {
        return get_current_user_id();
    }
}
