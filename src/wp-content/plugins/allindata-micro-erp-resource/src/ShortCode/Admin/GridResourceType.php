<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\ShortCode\Admin;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class GridResourceType
 * @package AllInData\MicroErp\Resource\ShortCode\Admin
 */
class GridResourceType extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Resource\Block\Admin\GridResourceType
     */
    private $block;

    /**
     * GridUser constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Resource\Block\Admin\GridResourceType$block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Resource\Block\Admin\GridResourceType$block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_resource_admin_grid_resource_type', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in() || !current_user_can('administrator') || is_admin()) {
            return '';
        }
        $this->getTemplate('admin/grid-resource-type', [
            'block' => $this->block
        ]);
    }
}