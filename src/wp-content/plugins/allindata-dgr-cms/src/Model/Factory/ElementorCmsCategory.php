<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model\Factory;

use Elementor\Core\Common\Modules\Finder\Base_Category as Entity;
use AllInData\Dgr\Core\Model\AbstractElementorFactory;

/**
 * Class ElementorCmsCategory
 * @package AllInData\Dgr\Cms\Model\Factory
 */
class ElementorCmsCategory extends AbstractElementorFactory
{
    /**
     * @param array $data
     * @return \AllInData\Dgr\Cms\Model\ElementorCmsCategory
     */
    public function create(array $data = []): Entity
    {
        return parent::create($data);
    }
}