<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Widget\Elementor\Admin;

use AllInData\Dgr\Cms\Model\ElementorCmsAdminCategory;
use AllInData\Dgr\Core\Widget\AbstractElementorWidget;

/**
 * Class ListOfUserAccounts
 * @package AllInData\Dgr\Cms\Widget\Elementor
 */
class ListOfUserAccounts extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-dgr-list-of-user-accounts';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('List of User Accounts', AID_DGR_CMS_TEXTDOMAIN);
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
        return [ElementorCmsAdminCategory::CATEGORY_NAME];
    }

    /**
     * @inheritDoc
     */
    protected function render()
    {
        echo '<div class="'.$this->get_name().'-elementor-widget">';
        echo do_shortcode("[dgr_cms_admin_grid_user]");
        echo '</div>';
    }
}