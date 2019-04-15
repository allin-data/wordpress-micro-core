<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\ShortCode;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class LoginForm
 * @package AllInData\MicroErp\Auth\ShortCode
 */
class LoginForm extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Auth\Block\Login
     */
    private $block;

    /**
     * Calendar constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Auth\Block\Login $block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Auth\Block\Login $block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_auth_login_form', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (is_admin()) {
            return '';
        }
        $this->getTemplate('form-login', [
            'block' => $this->block
        ]);
    }
}