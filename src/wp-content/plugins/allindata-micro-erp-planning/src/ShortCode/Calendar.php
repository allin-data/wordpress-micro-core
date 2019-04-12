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

        $attributes = $this->prepareAttributes($attributes, [
            'id' => '',
            'title' => __('Calendar', AID_MICRO_ERP_MDM_TEXTDOMAIN),
            'default-view' => 'month',
            'label-milestone' => __('Milestone', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-task' => __('Task', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-all_day' => __('All Day', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-new_schedule' => __('New Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-more_events' => __('See %1$s more events', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-going_time' => __('GoingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-coming_time' => __('ComingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-sunday' => __('Sunday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-monday' => __('Monday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-tuesday' => __('Tuesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-wednesday' => __('Wednesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-thursday' => __('Thursday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-friday' => __('Friday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-saturday' => __('Saturday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-schedule_monthly' => __('Monthly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-schedule_weekly' => __('Weekly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'label-schedule_daily' => __('Daily Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
        ], $name);
        $this->block->setAttributes($attributes);

        $this->getTemplate('calendar', [
            'block' => $this->block
        ]);
    }
}