<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\ShortCode\Admin;

use AllInData\Dgr\Core\ShortCode\AbstractShortCode;
use AllInData\Dgr\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class GridUser
 * @package AllInData\Dgr\Cms\ShortCode\Admin
 */
class GridUser extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\Dgr\Cms\Block\Admin\GridUser
     */
    private $blockUserGrid;

    /**
     * GridUser constructor.
     * @param string $templatePath
     * @param \AllInData\Dgr\Cms\Block\Admin\GridUser $blockUserGrid
     */
    public function __construct(
        string $templatePath,
        \AllInData\Dgr\Cms\Block\Admin\GridUser $blockUserGrid
    ) {
        parent::__construct($templatePath);
        $this->blockUserGrid = $blockUserGrid;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('dgr_cms_admin_grid_user', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in() || !current_user_can('administrator')) {
            return '';
        }
        $this->getTemplate('admin/grid-user', [
            'block' => $this->blockUserGrid
        ]);
    }
}