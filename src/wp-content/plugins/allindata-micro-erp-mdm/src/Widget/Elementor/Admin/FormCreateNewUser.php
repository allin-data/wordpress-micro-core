<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Widget\Elementor\Admin;

use AllInData\MicroErp\Mdm\Model\ElementorMdmAdminCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;

/**
 * Class FormCreateNewUser
 * @package AllInData\MicroErp\Mdm\Widget\Elementor\Admin
 */
class FormCreateNewUser extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-micro-erp-form-create-new-user';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('Form: Create a new User', AID_MICRO_ERP_MDM_TEXTDOMAIN);
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
        return [ElementorMdmAdminCategory::CATEGORY_NAME];
    }

    /**
     * @inheritDoc
     */
    protected function render()
    {
        echo '<div class="'.$this->get_name().'-elementor-widget">';
        echo do_shortcode("[micro_erp_mdm_admin_form_create_user]");
        echo '</div>';
    }
}