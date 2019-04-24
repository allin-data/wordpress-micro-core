<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Widget\Elementor\Admin;

use AllInData\MicroErp\Resource\Model\ElementorResourceAdminCategory as ElementorCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;

/**
 * Class ListOfResourceTypes
 * @package AllInData\MicroErp\Resource\Widget\Elementor\Admin
 */
class ListOfResourceTypes extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-micro-erp-resource-list-of-resource-types';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('List of Resource Types', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-files-o';
    }

    /**
     * @inheritDoc
     */
    public function get_categories()
    {
        return [ElementorCategory::CATEGORY_NAME];
    }

    /**
     * @inheritDoc
     */
    protected function render()
    {
        echo '<div class="'.$this->get_name().'-elementor-widget">';
        echo do_shortcode("[micro_erp_resource_admin_grid_resource_type]");
        echo '</div>';
    }
}