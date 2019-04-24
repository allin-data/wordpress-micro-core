<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\ShortCode;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Resource\Model\Capability\CreateResource;

/**
 * Class GridResource
 * @package AllInData\MicroErp\Resource\ShortCode
 */
class GridResource extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Resource\Block\GridResource
     */
    private $block;

    /**
     * GridUser constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Resource\Block\GridResource $block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Resource\Block\GridResource $block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_resource_grid_resource', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in() ||
            is_admin()) {
            return '';
        }

        $attributes = $this->prepareAttributes($attributes, [
            'title' => __('Resource Creation', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
            'label' => __('Resource', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN)
        ], $name);
        $this->block->setAttributes($attributes);

        $this->getTemplate('grid-resource', [
            'block' => $this->block
        ]);
    }
}