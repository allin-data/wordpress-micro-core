<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model;

use Elementor\Core\Common\Modules\Finder\Base_Category;

/**
 * Class ElementorCmsAdminCategory
 * @package AllInData\Dgr\Cms\Model
 */
class ElementorCmsAdminCategory extends Base_Category
{
    const CATEGORY_NAME = 'allindata-dgr-cms-admin-category';

    /**
     * @return string|void
     */
    public function get_title()
    {
        return __('DGR CMS Administration', AID_DGR_CMS_TEXTDOMAIN);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return static::CATEGORY_NAME;
    }

    /**
     * @param array $options
     * @return array
     */
    public function get_category_items(array $options = [])
    {
        return [];
    }
}
