<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\ShortCode;

/**
 * Class AbstractAdminShortCode
 * @package AllInData\Micro\Core\ShortCode
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
