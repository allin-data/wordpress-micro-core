<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\ShortCode\Admin;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class FormEditResourceTypeAttributes
 * @package AllInData\MicroErp\Resource\ShortCode\Admin
 */
class FormEditResourceTypeAttributes extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Resource\Block\Admin\FormEditResourceTypeAttributes
     */
    private $block;

    /**
     * FormCreateUser constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Resource\Block\Admin\FormEditResourceTypeAttributes $block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Resource\Block\Admin\FormEditResourceTypeAttributes $block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_resource_admin_form_create_resource_type_attribute', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in() || !current_user_can('administrator') || is_admin()) {
            return '';
        }

        $attributes = $this->prepareAttributes($attributes, [
            'resource_type_id' => null
        ], $name);
        $block = clone $this->block;
        $block->setAttributes($attributes);

        $this->getTemplate('admin/form-create-resource-type-attribute', [
            'block' => $block
        ]);
    }
}