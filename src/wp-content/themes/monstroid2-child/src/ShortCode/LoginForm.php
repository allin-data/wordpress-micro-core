<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme\ShortCode;

/**
 * Class LoginForm
 * @package AllInData\Dgr\Theme\Controller
 */
class LoginForm implements ThemeShortCodeInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('dgr_login_form', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (is_admin()) {
            return;
        }
        get_template_part('templates/form/login');
    }
}