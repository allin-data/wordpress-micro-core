<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Block\Admin;

use AllInData\MicroErp\Mdm\Controller\Admin\CreateUser;
use AllInData\MicroErp\Core\Block\AbstractBlock;

/**
 * Class FormCreateUser
 * @package AllInData\MicroErp\Mdm\Block\Admin
 */
class FormCreateUser extends AbstractBlock
{
    /**
     * @return string
     */
    public function getCreateUserActionSlug()
    {
        return CreateUser::ACTION_SLUG;
    }
}