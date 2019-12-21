<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\ShortCode;

/**
 * Class AbstractNonAnonymousShortCode
 * @package AllInData\MicroErp\Core\ShortCode
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
