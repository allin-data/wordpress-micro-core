<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model;

use Elementor\Core\Common\Modules\Finder\Base_Category;

/**
 * Class ElementorResourceCategory
 * @package AllInData\MicroErp\Resource\Model
 */
class ElementorResourceCategory extends Base_Category
{
    const CATEGORY_NAME = 'allindata-micro-erp-resource-category';

    /**
     * @return string|void
     */
    public function get_title()
    {
        return __('Micro ERP Resource', AID_MICRO_ERP_MDM_TEXTDOMAIN);
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
