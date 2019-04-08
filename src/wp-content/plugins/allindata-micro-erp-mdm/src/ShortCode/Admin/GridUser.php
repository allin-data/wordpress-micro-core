<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\ShortCode\Admin;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class GridUser
 * @package AllInData\MicroErp\Mdm\ShortCode\Admin
 */
class GridUser extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Mdm\Block\Admin\GridUser
     */
    private $blockUserGrid;

    /**
     * GridUser constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Mdm\Block\Admin\GridUser $blockUserGrid
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Mdm\Block\Admin\GridUser $blockUserGrid
    ) {
        parent::__construct($templatePath);
        $this->blockUserGrid = $blockUserGrid;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_mdm_admin_grid_user', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (!is_user_logged_in() || !current_user_can('administrator')) {
            return '';
        }
        if (is_admin()) {
            return '';
        }
        $this->getTemplate('admin/grid-user', [
            'block' => $this->blockUserGrid
        ]);
    }
}