<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\ShortCode;

/**
 * Class AbstractAdminShortCode
 * @package AllInData\MicroErp\Core\ShortCode
 */
abstract class AbstractAdminShortCode extends AbstractShortCode
{
    /**
     * @return bool
     */
    protected function beforeExecute(): bool
    {
        if (!is_admin()) {
            return false;
        }
        return true;
    }
}
