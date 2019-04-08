<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Widget\Elementor\Admin;

use AllInData\MicroErp\Mdm\Model\ElementorMdmAdminCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;

/**
 * Class ListOfUserAccounts
 * @package AllInData\MicroErp\Mdm\Widget\Elementor
 */
class ListOfUserAccounts extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-micro-erp-list-of-user-accounts';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('List of User Accounts', AID_MICRO_ERP_MDM_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-users';
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
        echo do_shortcode("[micro_erp_mdm_admin_grid_user]");
        echo '</div>';
    }
}