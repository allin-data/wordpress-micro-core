<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\ShortCode;

/**
 * Class AbstractNonAnonymousShortCode
 * @package AllInData\Micro\Core\ShortCode
 */
abstract class AbstractNonAnonymousShortCode extends AbstractShortCode
{
    /**
     * @return bool
     */
    protected function beforeExecute(): bool
    {
        if (!is_user_logged_in()) {
            return false;
        }
        return true;
    }
}
