<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Factory;

use Elementor\Core\Common\Modules\Finder\Base_Category as Entity;
use AllInData\MicroErp\Core\Model\AbstractElementorFactory;

/**
 * Class ElementorMdmAdminCategory
 * @package AllInData\MicroErp\Mdm\Model\Factory
 */
class ElementorMdmAdminCategory extends AbstractElementorFactory
{
    /**
     * @param array $data
     * @return \AllInData\MicroErp\Mdm\Model\ElementorMdmAdminCategory
     */
    public function create(array $data = []): Entity
    {
        return parent::create($data);
    }
}