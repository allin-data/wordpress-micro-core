<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\ShortCode;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class UserProfile
 * @package AllInData\MicroErp\Mdm\ShortCode
 */
class UserProfile extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Mdm\Block\UserProfile
     */
    private $block;

    /**
     * GridUser constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Mdm\Block\UserProfile $block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Mdm\Block\UserProfile $block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_mdm_user_profile', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in() || is_admin()) {
            return '';
        }
        $this->getTemplate('user-profile', [
            'block' => $this->block
        ]);
    }
}