<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Widget\Elementor;

use AllInData\Dgr\Cms\Model\ElementorCmsCategory;
use AllInData\Dgr\Core\Widget\AbstractElementorWidget;

/**
 * Class UserProfileForm
 * @package AllInData\Dgr\Cms\Widget\Elementor
 */
class UserProfileForm extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-dgr-user-profile-form';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('User Profile Form', AID_DGR_CMS_TEXTDOMAIN);
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
        return [ElementorCmsCategory::CATEGORY_NAME];
    }

    /**
     * @inheritDoc
     */
    protected function render()
    {
        echo '<div class="'.$this->get_name().'-elementor-widget">';
        echo do_shortcode("[dgr_cms_user_profile]");
        echo '</div>';
    }
}