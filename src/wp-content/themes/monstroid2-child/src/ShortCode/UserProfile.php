<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme\ShortCode;

/**
 * Class UserProfile
 * @package AllInData\Dgr\Theme\ShortCode
 */
class UserProfile implements ThemeShortCodeInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('dgr_user_profile', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (is_admin()) {
            return;
        }
        get_template_part('templates/user/profile');
    }
}