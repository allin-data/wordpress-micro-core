<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\ShortCode\Admin;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class FormCreateUser
 * @package AllInData\MicroErp\Mdm\ShortCode\Admin
 */
class FormCreateResourceType extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Resource\Block\Admin\FormCreateResourceType
     */
    private $block;

    /**
     * FormCreateUser constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Resource\Block\Admin\FormCreateResourceType $block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Resource\Block\Admin\FormCreateResourceType $block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_resource_admin_form_create_resource_type', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in() || !current_user_can('administrator') || is_admin()) {
            return '';
        }
        $this->getTemplate('admin/form-create-resource-type', [
            'block' => $this->block
        ]);
    }
}