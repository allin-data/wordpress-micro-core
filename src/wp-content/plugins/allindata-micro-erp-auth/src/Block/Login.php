<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;

/**
 * Class Login
 * @package AllInData\MicroErp\Auth\Block
 */
class Login extends AbstractBlock
{
    /**
     * @return string
     */
    public function getCreateUserActionSlug()
    {
        return \AllInData\MicroErp\Auth\Controller\Login::ACTION_SLUG;
    }

    /**
     * @return string
     */
    public function getFormRedirectUrl()
    {
        return home_url();
    }
}