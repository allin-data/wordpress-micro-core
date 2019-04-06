<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\ShortCode;

use AllInData\Dgr\Core\ShortCode\AbstractShortCode;
use AllInData\Dgr\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class UserProfile
 * @package AllInData\Dgr\Cms\ShortCode
 */
class UserProfile extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('dgr_cms_user_profile', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in()) {
            return '';
        }
        if (is_admin()) {
            return '';
        }
        $this->getTemplate('user-profile');
    }
}