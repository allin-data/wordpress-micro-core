<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Widget\Elementor;

use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;
use AllInData\MicroErp\Mdm\Model\ElementorMdmCategory;

/**
 * Class LoginForm
 * @package AllInData\MicroErp\Auth\Widget\Elementor
 */
class LoginForm extends AbstractElementorWidget
{
    const WIDGET_NAME = 'allindata-micro-erp-auth-login-form';

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
        return __('Login Form', AID_MICRO_ERP_AUTH_TEXTDOMAIN);
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
        echo do_shortcode("[micro_erp_auth_login_form]");
        echo '</div>';
    }
}