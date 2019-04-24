<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Widget\Elementor\Admin;

use AllInData\MicroErp\Resource\Model\ElementorResourceAdminCategory as ElementorCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;

/**
 * Class FormCreateNewResourceType
 * @package AllInData\MicroErp\Resource\Widget\Elementor\Admin
 */
class FormCreateNewResourceType extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-micro-erp-resource-form-create-new-resource-type';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('Form: Create a new Resource Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-file-o';
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
        echo do_shortcode("[micro_erp_resource_admin_form_create_resource_type]");
        echo '</div>';
    }
}