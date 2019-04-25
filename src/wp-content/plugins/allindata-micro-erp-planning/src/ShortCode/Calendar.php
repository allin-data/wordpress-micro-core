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
        if (!is_user_logged_in()) {
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
            'advanced_style_common_border_px' => 1,
            'advanced_style_common_border_style' => 'solid',
            'advanced_style_common_border_color' => '#e5e5e',
            'advanced_style_common_backgroundColor' => '#fff',
            'advanced_style_common_holiday_color' => '#ff4040',
            'advanced_style_common_saturday_color' => '#333',
            'advanced_style_common_dayname_color' => '#333',
            'advanced_style_common_today_color' => '#333',
            'advanced_style_common_creationGuide_backgroundColor' => 'rgba(81, 92, 230, 0.05)',
            'advanced_style_common_creationGuide_border_px' => 1,
            'advanced_style_common_creationGuide_border_style' => 'solid',
            'advanced_style_common_creationGuide_border_color' => '#515ce6',
            'advanced_style_month_dayname_height' => 31,
            'advanced_style_month_dayname_borderLeft_px' => 1,
            'advanced_style_month_dayname_borderLeft_style' => 'solid',
            'advanced_style_month_dayname_borderLeft_color' => '#e5e5e5',
            'advanced_style_month_dayname_paddingLeft' => 10,
            'advanced_style_month_dayname_paddingRight' => 10,
            'advanced_style_month_dayname_backgroundColor' => 'inherit',
            'advanced_style_month_dayname_fontSize' => 12,
            'advanced_style_month_dayname_fontWeight' => 'normal',
            'advanced_style_month_dayname_textAlign' => 'left',
            'advanced_style_month_holidayExceptThisMonth_color' => 'rgba(255, 64, 64, 0.4)',
            'advanced_style_month_dayExceptThisMonth_color' => 'rgba(51, 51, 51, 0.4)',
            'advanced_style_month_weekend_backgroundColor' => 'inherit',
            'advanced_style_month_day_fontSize' => 14,
            'advanced_style_month_schedule_borderRadius' => 2,
            'advanced_style_month_schedule_height' => 24,
            'advanced_style_month_schedule_marginTop' => 2,
            'advanced_style_month_schedule_marginLeft' => 8,
            'advanced_style_month_schedule_marginRight' => 8,
            'advanced_style_month_moreView_border_px' => 1,
            'advanced_style_month_moreView_border_style' => 'solid',
            'advanced_style_month_moreView_boxShadow_px_top' => 0,
            'advanced_style_month_moreView_boxShadow_px_right' => 2,
            'advanced_style_month_moreView_boxShadow_px_bottom' => 6,
            'advanced_style_month_moreView_boxShadow_px_left' => 0,
            'advanced_style_month_moreView_boxShadow_color' => 'rgba(0, 0, 0, 0.1)',
            'advanced_style_month_moreView_backgroundColor' => '#fff',
            'advanced_style_month_moreView_paddingBottom' => 17,
            'advanced_style_month_moreViewTitle_height' => 44,
            'advanced_style_month_moreViewTitle_marginBottom' => 12,
            'advanced_style_month_moreViewTitle_backgroundColor' => 'inherit',
            'advanced_style_month_moreViewTitle_borderBottom_px' => 0,
            'advanced_style_month_moreViewTitle_borderBottom_style' => 'solid',
            'advanced_style_month_moreViewTitle_borderBottom_color' => 'transparent',
            'advanced_style_month_moreViewTitle_padding_px_top' => 12,
            'advanced_style_month_moreViewTitle_padding_px_right' => 17,
            'advanced_style_month_moreViewTitle_padding_px_bottom' => 0,
            'advanced_style_month_moreViewTitle_padding_px_left' => 17,
            'advanced_style_month_moreViewList_padding_px_top' => 0,
            'advanced_style_month_moreViewList_padding_px_right' => 17,
            'advanced_style_month_moreViewList_padding_px_bottom' => 0,
            'advanced_style_month_moreViewList_padding_px_left' => 17,
            'advanced_style_week_dayname_height' => 42,
            'advanced_style_week_dayname_borderTop_px' => 1,
            'advanced_style_week_dayname_borderTop_style' => 'solid',
            'advanced_style_week_dayname_borderTop_color' => '#e5e5e5',
            'advanced_style_week_dayname_borderBottom_px' => 1,
            'advanced_style_week_dayname_borderBottom_style' => 'solid',
            'advanced_style_week_dayname_borderBottom_color' => '#e5e5e5',
            'advanced_style_week_dayname_borderLeft_px' => 1,
            'advanced_style_week_dayname_borderLeft_style' => 'solid',
            'advanced_style_week_dayname_borderLeft_color' => '#e5e5e5',
            'advanced_style_week_dayname_paddingLeft' => 10,
            'advanced_style_week_dayname_backgroundColor' => 'inherit',
            'advanced_style_week_dayname_textAlign' => 'left',
            'advanced_style_week_today_color' => '#333',
            'advanced_style_week_pastDay_color' => '#bbb',
            'advanced_style_week_vpanelSplitter_border_px' => 1,
            'advanced_style_week_vpanelSplitter_border_style' => 'solid',
            'advanced_style_week_vpanelSplitter_border_color' => '#e5e5e5',
            'advanced_style_week_vpanelSplitter_height' => 3,
            'advanced_style_week_daygrid_borderRight_px' => 1,
            'advanced_style_week_daygrid_borderRight_style' => 'solid',
            'advanced_style_week_daygrid_borderRight_color' => '#e5e5e5',
            'advanced_style_week_daygrid_backgroundColor' => 'inherit',
            'advanced_style_week_daygridLeft_width' => 72,
            'advanced_style_week_daygridLeft_backgroundColor' => 'inherit',
            'advanced_style_week_daygridLeft_paddingRight' => 8,
            'advanced_style_week_daygridLeft_borderRight_px' => 1,
            'advanced_style_week_daygridLeft_borderRight_style' => 'solid',
            'advanced_style_week_daygridLeft_borderRight_color' => '#e5e5e5',
            'advanced_style_week_today_backgroundColor' => 'rgba(81, 92, 230, 0.05)',
            'advanced_style_week_weekend_backgroundColor' => 'inherit',
            'advanced_style_week_timegridLeft_width' => 72,
            'advanced_style_week_timegridLeft_backgroundColor' => 'inherit',
            'advanced_style_week_timegridLeft_borderRight_px' => 1,
            'advanced_style_week_timegridLeft_borderRight_style' => 'solid',
            'advanced_style_week_timegridLeft_borderRight_color' => '#e5e5e5',
            'advanced_style_week_timegridLeft_fontSize' => 11,
            'advanced_style_week_timegridLeftTimezoneLabel_height' => 40,
            'advanced_style_week_timegridLeftAdditionalTimezone_backgroundColor' => '#fff',
            'advanced_style_week_timegridOneHour_height' => 52,
            'advanced_style_week_timegridHalfHour_height' => 26,
            'advanced_style_week_timegridHalfHour_borderBottom_px' => 0,
            'advanced_style_week_timegridHalfHour_borderBottom_style' => 'solid',
            'advanced_style_week_timegridHalfHour_borderBottom_color' => 'transparent',
            'advanced_style_week_timegridHorizontalLine_borderBottom_px' => 1,
            'advanced_style_week_timegridHorizontalLine_borderBottom_style' => 'solid',
            'advanced_style_week_timegridHorizontalLine_borderBottom_color' => '#e5e5e5',
            'advanced_style_week_timegrid_paddingRight' => 8,
            'advanced_style_week_timegrid_borderRight_px' => 1,
            'advanced_style_week_timegrid_borderRight_style' => 'solid',
            'advanced_style_week_timegrid_borderRight_color' => '#e5e5e5',
            'advanced_style_week_timegridSchedule_borderRadius' => 2,
            'advanced_style_week_timegridSchedule_paddingLeft' => 2,
            'advanced_style_week_currentTime_color' => '#515ce6',
            'advanced_style_week_currentTime_fontSize' => 11,
            'advanced_style_week_currentTime_fontWeight' => 'normal',
            'advanced_style_week_pastTime_color' => '#bbb',
            'advanced_style_week_pastTime_fontWeight' => 'normal',
            'advanced_style_week_futureTime_color' => '#bbb',
            'advanced_style_week_futureTime_fontWeight' => 'normal',
            'advanced_style_week_currentTimeLinePast_border_px' => 1,
            'advanced_style_week_currentTimeLinePast_border_style' => 'dashed',
            'advanced_style_week_currentTimeLinePast_border_color' => '#515ce6',
            'advanced_style_week_currentTimeLineBullet_backgroundColor' => '#515ce6',
            'advanced_style_week_currentTimeLineToday_border_px' => 1,
            'advanced_style_week_currentTimeLineToday_border_style' => 'solid',
            'advanced_style_week_currentTimeLineToday_border_color' => '#515ce6',
            'advanced_style_week_currentTimeLineFuture_border_px' => 0,
            'advanced_style_week_currentTimeLineFuture_border_style' => 'solid',
            'advanced_style_week_currentTimeLineFuture_border_color' => 'transparent',
            'advanced_style_week_creationGuide_color' => '#515ce6',
            'advanced_style_week_creationGuide_fontSize' => 11,
            'advanced_style_week_creationGuide_fontWeight' => 'bold',
            'advanced_style_week_dayGridSchedule_borderRadius' => 2,
            'advanced_style_week_dayGridSchedule_height' => 24,
            'advanced_style_week_dayGridSchedule_marginTop' => 2,
            'advanced_style_week_dayGridSchedule_marginLeft' => 8,
            'advanced_style_week_dayGridSchedule_marginRight' => 8,
        ], $name);
        $this->block->setAttributes($attributes);

        $this->getTemplate('calendar', [
            'block' => $this->block
        ]);
    }
}