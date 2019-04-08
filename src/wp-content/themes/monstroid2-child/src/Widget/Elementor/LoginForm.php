<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\Widget\Elementor;

use AllInData\MicroErp\Mdm\Model\ElementorMdmCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;

/**
 * Class FooBar
 * @package AllInData\MicroErp\Mdm\Widget\Elementor
 */
class LoginForm extends AbstractElementorWidget
{
    const WIDGET_NAME = 'allindata-micro-erp-theme-login-form';

    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return static::WIDGET_NAME;
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('Login Form', AID_MICRO_ERP_MDM_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-sign-in';
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
        echo do_shortcode("[micro_erp_login_form]");
        echo '</div>';
    }
}