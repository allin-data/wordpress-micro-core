<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Block\Admin;

use AllInData\MicroErp\Core\Block\AbstractPaginationBlock;
use AllInData\MicroErp\Resource\Controller\Admin\UpdateResourceType;
use AllInData\MicroErp\Resource\Model\ResourceType;

/**
 * Class GridResourceType
 * @package AllInData\MicroErp\Resource\Block\Admin
 */
class GridResourceType extends AbstractPaginationBlock
{
    /**
     * @return ResourceType[]
     */
    public function getResourceTypes(): array
    {
        return $this->getPagination()->load();
    }

    /**
     * @return string
     */
    public function getUpdateActionSlug()
    {
        return UpdateResourceType::ACTION_SLUG;
    }
}