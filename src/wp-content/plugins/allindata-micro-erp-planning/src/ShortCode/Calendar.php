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
        if (!is_user_logged_in() || is_admin()) {
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
            'label-schedule_daily' => __('Daily Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            'advanced-style_common.border.px' => 1,
            'advanced-style_common.border.style' => 'solid',
            'advanced-style_common.border.color' => '#e5e5e',
            'advanced-style_common.backgroundColor' => '#fff',
            'advanced-style_common.holiday.color' => '#ff4040',
            'advanced-style_common.saturday.color' => '#333',
            'advanced-style_common.dayname.color' => '#333',
            'advanced-style_common.today.color' => '#333',
            'advanced-style_common.creationGuide.backgroundColor' => 'rgba(81, 92, 230, 0.05)',
            'advanced-style_common.creationGuide.border.px' => 1,
            'advanced-style_common.creationGuide.border.style' => 'solid',
            'advanced-style_common.creationGuide.border.color' => '#515ce6',
            'advanced-style_month.dayname.height' => 31,
            'advanced-style_month.dayname.borderLeft.px' => 1,
            'advanced-style_month.dayname.borderLeft.style' => 'solid',
            'advanced-style_month.dayname.borderLeft.color' => '#e5e5e5',
            'advanced-style_month.dayname.paddingLeft' => 10,
            'advanced-style_month.dayname.paddingRight' => 10,
            'advanced-style_month.dayname.backgroundColor' => 'inherit',
            'advanced-style_month.dayname.fontSize' => 12,
            'advanced-style_month.dayname.fontWeight' => 'normal',
            'advanced-style_month.dayname.textAlign' => 'left',
            'advanced-style_month.holidayExceptThisMonth.color' => 'rgba(255, 64, 64, 0.4)',
            'advanced-style_month.dayExceptThisMonth.color' => 'rgba(51, 51, 51, 0.4)',
            'advanced-style_month.weekend.backgroundColor' => 'inherit',
            'advanced-style_month.day.fontSize' => 14,
            'advanced-style_month.schedule.borderRadius' => 2,
            'advanced-style_month.schedule.height' => 24,
            'advanced-style_month.schedule.marginTop' => 2,
            'advanced-style_month.schedule.marginLeft' => 8,
            'advanced-style_month.schedule.marginRight' => 8,
            'advanced-style_month.moreView.border.px' => 1,
            'advanced-style_month.moreView.border.style' => 'solid',
            'advanced-style_month.moreView.boxShadow.px.top' => 0,
            'advanced-style_month.moreView.boxShadow.px.right' => 2,
            'advanced-style_month.moreView.boxShadow.px.bottom' => 6,
            'advanced-style_month.moreView.boxShadow.px.left' => 0,
            'advanced-style_month.moreView.boxShadow.color' => 'rgba(0, 0, 0, 0.1)',
            'advanced-style_month.moreView.backgroundColor' => '#fff',
            'advanced-style_month.moreView.paddingBottom' => 17,
            'advanced-style_month.moreViewTitle.height' => 44,
            'advanced-style_month.moreViewTitle.marginBottom' => 12,
            'advanced-style_month.moreViewTitle.backgroundColor' => 'inherit',
            'advanced-style_month.moreViewTitle.borderBottom.px' => 0,
            'advanced-style_month.moreViewTitle.borderBottom.style' => 'solid',
            'advanced-style_month.moreViewTitle.borderBottom.color' => 'transparent',
            'advanced-style_month.moreViewTitle.padding.px.top' => 12,
            'advanced-style_month.moreViewTitle.padding.px.right' => 17,
            'advanced-style_month.moreViewTitle.padding.px.bottom' => 0,
            'advanced-style_month.moreViewTitle.padding.px.left' => 17,
            'advanced-style_month.moreViewList.padding.px.top' => 0,
            'advanced-style_month.moreViewList.padding.px.right' => 17,
            'advanced-style_month.moreViewList.padding.px.bottom' => 0,
            'advanced-style_month.moreViewList.padding.px.left' => 17,
            'advanced-style_week.dayname.height' => 42,
            'advanced-style_week.dayname.borderTop.px' => 1,
            'advanced-style_week.dayname.borderTop.style' => 'solid',
            'advanced-style_week.dayname.borderTop.color' => '#e5e5e5',
            'advanced-style_week.dayname.borderBottom.px' => 1,
            'advanced-style_week.dayname.borderBottom.style' => 'solid',
            'advanced-style_week.dayname.borderBottom.color' => '#e5e5e5',
            'advanced-style_week.dayname.borderLeft.px' => 1,
            'advanced-style_week.dayname.borderLeft.style' => 'solid',
            'advanced-style_week.dayname.borderLeft.color' => '#e5e5e5',
            'advanced-style_week.dayname.paddingLeft' => 10,
            'advanced-style_week.dayname.backgroundColor' => 'inherit',
            'advanced-style_week.dayname.textAlign' => 'left',
            'advanced-style_week.today.color' => '#333',
            'advanced-style_week.pastDay.color' => '#bbb',
            'advanced-style_week.vpanelSplitter.border.px' => 1,
            'advanced-style_week.vpanelSplitter.border.style' => 'solid',
            'advanced-style_week.vpanelSplitter.border.color' => '#e5e5e5',
            'advanced-style_week.vpanelSplitter.height' => 3,
            'advanced-style_week.daygrid.borderRight.px' => 1,
            'advanced-style_week.daygrid.borderRight.style' => 'solid',
            'advanced-style_week.daygrid.borderRight.color' => '#e5e5e5',
            'advanced-style_week.daygrid.backgroundColor' => 'inherit',
            'advanced-style_week.daygridLeft.width' => 72,
            'advanced-style_week.daygridLeft.backgroundColor' => 'inherit',
            'advanced-style_week.daygridLeft.paddingRight' => 8,
            'advanced-style_week.daygridLeft.borderRight.px' => 1,
            'advanced-style_week.daygridLeft.borderRight.style' => 'solid',
            'advanced-style_week.daygridLeft.borderRight.color' => '#e5e5e5',
            'advanced-style_week.today.backgroundColor' => 'rgba(81, 92, 230, 0.05)',
            'advanced-style_week.weekend.backgroundColor' => 'inherit',
            'advanced-style_week.timegridLeft.width' => 72,
            'advanced-style_week.timegridLeft.backgroundColor' => 'inherit',
            'advanced-style_week.timegridLeft.borderRight.px' => 1,
            'advanced-style_week.timegridLeft.borderRight.style' => 'solid',
            'advanced-style_week.timegridLeft.borderRight.color' => '#e5e5e5',
            'advanced-style_week.timegridLeft.fontSize' => 11,
            'advanced-style_week.timegridLeftTimezoneLabel.height' => 40,
            'advanced-style_week.timegridLeftAdditionalTimezone.backgroundColor' => '#fff',
            'advanced-style_week.timegridOneHour.height' => 52,
            'advanced-style_week.timegridHalfHour.height' => 26,
            'advanced-style_week.timegridHalfHour.borderBottom.px' => 0,
            'advanced-style_week.timegridHalfHour.borderBottom.style' => 'solid',
            'advanced-style_week.timegridHalfHour.borderBottom.color' => 'transparent',
            'advanced-style_week.timegridHorizontalLine.borderBottom.px' => 1,
            'advanced-style_week.timegridHorizontalLine.borderBottom.style' => 'solid',
            'advanced-style_week.timegridHorizontalLine.borderBottom.color' => '#e5e5e5',
            'advanced-style_week.timegrid.paddingRight' => 8,
            'advanced-style_week.timegrid.borderRight.px' => 1,
            'advanced-style_week.timegrid.borderRight.style' => 'solid',
            'advanced-style_week.timegrid.borderRight.color' => '#e5e5e5',
            'advanced-style_week.timegridSchedule.borderRadius' => 2,
            'advanced-style_week.timegridSchedule.paddingLeft' => 2,
            'advanced-style_week.currentTime.color' => '#515ce6',
            'advanced-style_week.currentTime.fontSize' => 11,
            'advanced-style_week.currentTime.fontWeight' => 'normal',
            'advanced-style_week.pastTime.color' => '#bbb',
            'advanced-style_week.pastTime.fontWeight' => 'normal',
            'advanced-style_week.futureTime.color' => '#bbb',
            'advanced-style_week.futureTime.fontWeight' => 'normal',
            'advanced-style_week.currentTimeLinePast.border.px' => 1,
            'advanced-style_week.currentTimeLinePast.border.style' => 'dashed',
            'advanced-style_week.currentTimeLinePast.border.color' => '#515ce6',
            'advanced-style_week.currentTimeLineBullet.backgroundColor' => '#515ce6',
            'advanced-style_week.currentTimeLineToday.border.px' => 1,
            'advanced-style_week.currentTimeLineToday.border.style' => 'solid',
            'advanced-style_week.currentTimeLineToday.border.color' => '#515ce6',
            'advanced-style_week.currentTimeLineFuture.border.px' => 0,
            'advanced-style_week.currentTimeLineFuture.border.style' => 'solid',
            'advanced-style_week.currentTimeLineFuture.border.color' => 'transparent',
            'advanced-style_week.creationGuide.color' => '#515ce6',
            'advanced-style_week.creationGuide.fontSize' => 11,
            'advanced-style_week.creationGuide.fontWeight' => 'bold',
            'advanced-style_week.dayGridSchedule.borderRadius' => 2,
            'advanced-style_week.dayGridSchedule.height' => 24,
            'advanced-style_week.dayGridSchedule.marginTop' => 2,
            'advanced-style_week.dayGridSchedule.marginLeft' => 8,
            'advanced-style_week.dayGridSchedule.marginRight' => 8,
        ], $name);
        $this->block->setAttributes($attributes);

        $this->getTemplate('calendar', [
            'block' => $this->block
        ]);
    }
}