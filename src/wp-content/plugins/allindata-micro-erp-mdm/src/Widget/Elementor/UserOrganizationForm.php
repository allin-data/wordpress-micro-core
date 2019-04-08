<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Widget\Elementor;

use AllInData\MicroErp\Mdm\Model\ElementorMdmCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;

/**
 * Class UserOrganizationForm
 * @package AllInData\MicroErp\Mdm\Widget\Elementor
 */
class UserOrganizationForm extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-micro-erp-user-organization-form';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('User Organization Form', AID_MICRO_ERP_MDM_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-code';
    }

    /**
     * @inheritDoc
     */
    public function get_categories()
    {
        return [ElementorMdmCategory::CATEGORY_NAME];
    }

    /**
     * @inheritDoc
     */
    protected function render()
    {
        echo '<div class="'.$this->get_name().'-elementor-widget">';
        echo do_shortcode("[micro_erp_mdm_user_organization]");
        echo '</div>';
    }
}