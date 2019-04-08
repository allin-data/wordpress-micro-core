<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model;

use Elementor\Core\Common\Modules\Finder\Base_Category;

/**
 * Class ElementorMdmAdminCategory
 * @package AllInData\MicroErp\Mdm\Model
 */
class ElementorMdmAdminCategory extends Base_Category
{
    const CATEGORY_NAME = 'allindata-micro-erp-mdm-admin-category';

    /**
     * @return string|void
     */
    public function get_title()
    {
        return __('Micro ERP MDM Administration', AID_MICRO_ERP_MDM_TEXTDOMAIN);
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
