<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block\Admin;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Resource\Controller\Admin\CreateResourceType;

/**
 * Class FormCreateResourceType
 * @package AllInData\MicroErp\Resource\Block\Admin
 */
class FormCreateResourceType extends AbstractBlock
{
    /**
     * @return string
     */
    public function getCreateActionSlug()
    {
        return CreateResourceType::ACTION_SLUG;
    }
}