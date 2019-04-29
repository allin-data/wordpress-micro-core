<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\ShortCode;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Mdm\Model\Role\AdministratorRole;
use AllInData\MicroErp\Resource\Model\Capability\CreateResource;

/**
 * Class FormCreateResource
 * @package AllInData\MicroErp\Resource\ShortCode
 */
class FormCreateResource extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Resource\Block\FormCreateResource
     */
    private $block;

    /**
     * FormCreateUser constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Resource\Block\FormCreateResource $block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Resource\Block\FormCreateResource $block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_resource_form_create_resource', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in() ||
            !(current_user_can(CreateResource::CAPABILITY) || current_user_can(AdministratorRole::ROLE_LEVEL)) ||
            is_admin()) {
            return '';
        }

        $attributes = $this->prepareAttributes($attributes, [
            'title' => __('Resource Creation', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
            'label' => __('Resource', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
            'resource_type_id' => null
        ], $name);
        $this->block->setAttributes($attributes);

        $this->getTemplate('form-create-resource', [
            'block' => $this->block
        ]);
    }
}