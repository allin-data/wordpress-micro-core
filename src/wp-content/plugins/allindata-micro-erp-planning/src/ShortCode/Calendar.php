<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\ShortCode;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class Calendar
 * @package AllInData\MicroErp\Planning\ShortCode
 */
class Calendar extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Planning\Block\Calendar
     */
    private $block;

    /**
     * Calendar constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Planning\Block\Calendar $block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Planning\Block\Calendar $block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_planning_calendar', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (is_admin()) {
            return '';
        }
        $this->getTemplate('calendar', [
            'block' => $this->block
        ]);
    }
}