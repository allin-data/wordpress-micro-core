<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Widget\Elementor;

use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;
use AllInData\MicroErp\Mdm\Model\ElementorMdmCategory;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

/**
 * Class Calendar
 * @package AllInData\MicroErp\Planning\Widget\Elementor
 */
class Calendar extends AbstractElementorWidget
{
    const WIDGET_NAME = 'allindata-micro-erp-planning-calendar';

    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return static::WIDGET_NAME;
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('Calendar', AID_MICRO_ERP_PLANNING_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-calendar';
    }

    /**
     * @inheritDoc
     */
    public function get_categories()
    {
        return [ElementorMdmCategory::CATEGORY_NAME];
    }

    /**
     * @inheritDoc
     */
    protected function _register_controls()
    {
        /*
         * Section Main Configuration
         */
        $this->start_controls_section(
            'section_main_configuration',
            [
                'label' => __('Main Configuration', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'calendar_id',
            [
                'label' => __('ID', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter an unique calendar id', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => uniqid()
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => __('Title', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter the calendar title', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
            ]
        );

        $this->add_control(
            'default-view',
            [
                'label' => __('Default View', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'month' => __('Month', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                    'week' => __('Week', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                    'day' => __('Day', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                ],
                'default' => 'month',
            ]
        );

        $this->end_controls_section();

        /*
         * Section Labels General
         */
        $this->start_controls_section(
            'section_labels_general',
            [
                'label' => __('Labels (General)', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label-schedule_monthly',
            [
                'label' => __('Monthly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Monthly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Monthly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-schedule_weekly',
            [
                'label' => __('Weekly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Weekly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Weekly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-schedule_daily',
            [
                'label' => __('Daily Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Daily Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Daily Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );

        $this->end_controls_section();

        /*
         * Section Labels Calendar
         */
        $this->start_controls_section(
            'section_labels_calendar',
            [
                'label' => __('Labels (Calendar)', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label-milestone',
            [
                'label' => __('Milestone', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Milestone', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Milestone', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-task',
            [
                'label' => __('Task', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Task', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Task', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-all_day',
            [
                'label' => __('All Day', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('All Day', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('All Day', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-new_schedule',
            [
                'label' => __('New Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('New Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('New Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-more_events',
            [
                'label' => __('See %1$s more events', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('See %1$s more events', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('See %1$s more events', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-going_time',
            [
                'label' => __('GoingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('GoingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('GoingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-coming_time',
            [
                'label' => __('ComingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('ComingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('ComingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );

        $this->end_controls_section();

        /*
         * Section Labels Weekdays
         */
        $this->start_controls_section(
            'section_labels_weekdays',
            [
                'label' => __('Labels (Weekdays)', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label-sunday',
            [
                'label' => __('Sunday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Sunday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Sunday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-monday',
            [
                'label' => __('Monday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Monday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Monday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-tuesday',
            [
                'label' => __('Tuesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Tuesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Tuesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-wednesday',
            [
                'label' => __('Wednesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Wednesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Wednesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-thursday',
            [
                'label' => __('Thursday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Thursday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Thursday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-friday',
            [
                'label' => __('Friday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Friday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Friday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-saturday',
            [
                'label' => __('Saturday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Saturday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Saturday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );

        $this->end_controls_section();

        /*****
         *
         * Advanced Styling Configuration
         *
         *****/
        /*
         * Advanced Styling Configuration - Common
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_common',
            [
                'label' => __('Common Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'advanced_style_common_border_px',
            [
                'label' => __('Border Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_common_border_style',
            [
                'label' => __('Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_common_border_color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e',
                'default' => '#e5e5e',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_common_backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#fff',
                'default' => '#fff',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_common_holiday_color',
            [
                'label' => __('Holiday Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#ff4040',
                'default' => '#ff4040',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_common_saturday_color',
            [
                'label' => __('Saturday Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#333',
                'default' => '#333',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_common_dayname_color',
            [
                'label' => __('Dayname Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#333',
                'default' => '#333',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_common_today_color',
            [
                'label' => __('Today Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#333',
                'default' => '#333',
                'alpha' => true
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Common Creation Guide
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_common_creationguide',
            [
                'label' => __('Creation Guide Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_common_creationGuide_backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(81, 92, 230, 0.05)',
                'default' => 'rgba(81, 92, 230, 0.05)',
                'alpha' => true
            ]
        );

        $this->add_control(
            'advanced_style_common_creationGuide_border_px',
            [
                'label' => __('Border Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_common_creationGuide_border_style',
            [
                'label' => __('Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_common_creationGuide_border_color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Month - Dayname
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_month_dayname',
            [
                'label' => __('Month Dayname Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_height',
            [
                'label' => __('Dayname Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 31
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_borderLeft_px',
            [
                'label' => __('Border Left Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_borderLeft_style',
            [
                'label' => __('Border Left Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_borderLeft_color',
            [
                'label' => __('Border Left Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_paddingLeft',
            [
                'label' => __('Padding Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 25,
                'step' => 1,
                'default' => 10
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_paddingRight',
            [
                'label' => __('Padding Right [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 25,
                'step' => 1,
                'default' => 10
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_fontSize',
            [
                'label' => __('Font Size [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 12
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_fontWeight',
            [
                'label' => __('Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'normal',
                'default' => 'normal'
            ]
        );
        $this->add_control(
            'advanced_style_month_dayname_textAlign',
            [
                'label' => __('Text Align', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Month - Dayname
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_month_day_gridcell',
            [
                'label' => __('Month Day Grid Cell Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_month_holidayExceptThisMonth_color',
            [
                'label' => __('Holiday Except This Month Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(255, 64, 64, 0.4)',
                'default' => 'rgba(255, 64, 64, 0.4)',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_dayExceptThisMonth_color',
            [
                'label' => __('Day Except This Month Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(51, 51, 51, 0.4)',
                'default' => 'rgba(51, 51, 51, 0.4)',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_weekend_backgroundColor',
            [
                'label' => __('Weekend Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_day_fontSize',
            [
                'label' => __('Font Size [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 14
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Month - Schedule
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_month_schedule',
            [
                'label' => __('Month Schedule Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_month_schedule_borderRadius',
            [
                'label' => __('Border Radius [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control(
            'advanced_style_month_schedule_height',
            [
                'label' => __('Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 24
            ]
        );
        $this->add_control(
            'advanced_style_month_schedule_marginTop',
            [
                'label' => __('Margin Top [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control(
            'advanced_style_month_schedule_marginLeft',
            [
                'label' => __('Margin Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 8
            ]
        );
        $this->add_control(
            'advanced_style_month_schedule_marginRight',
            [
                'label' => __('Margin Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 8
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Month - More View
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_month_moreview',
            [
                'label' => __('Month More View Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_border_px',
            [
                'label' => __('Border Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_border_style',
            [
                'label' => __('Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_border_color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#d5d5d5',
                'default' => '#d5d5d5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_boxShadow_px_top',
            [
                'label' => __('Box Shadow Size Top [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 0
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_boxShadow_px_right',
            [
                'label' => __('Box Shadow Size Right [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_boxShadow_px_bottom',
            [
                'label' => __('Box Shadow Size Bottom [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 6
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_boxShadow_px_left',
            [
                'label' => __('Box Shadow Size Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 0
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_boxShadow_color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(0, 0, 0, 0.1)',
                'default' => 'rgba(0, 0, 0, 0.1)',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#fff',
                'default' => '#fff',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_moreView_paddingBottom',
            [
                'label' => __('Padding Bottom [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 17
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_height',
            [
                'label' => __('Title Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 44
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_marginBottom',
            [
                'label' => __('Title Margin Bottom [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 12
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_borderBottom_px',
            [
                'label' => __('Title Border Bottom Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 0
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_borderBottom_style',
            [
                'label' => __('Title Border Bottom Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_borderBottom_color',
            [
                'label' => __('Title Border Bottom Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'transparent',
                'default' => 'transparent',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_padding_px_top',
            [
                'label' => __('Title Padding Top [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 12
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_padding_px_right',
            [
                'label' => __('Title Padding Right [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 17
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_padding_px_bottom',
            [
                'label' => __('Title Padding Bottom [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 0
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewTitle_padding_px_left',
            [
                'label' => __('Title Padding Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 17
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewList_padding_px_top',
            [
                'label' => __('List Padding Top [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 0
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewList_padding_px_right',
            [
                'label' => __('List Padding Right [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 17
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewList_padding_px_bottom',
            [
                'label' => __('List Padding Bottom [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 0
            ]
        );
        $this->add_control(
            'advanced_style_month_moreViewList_padding_px_left',
            [
                'label' => __('List Padding Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 17
            ]
        );
        $this->end_controls_section();
        
        /*
         * Advanced Styling Configuration - Week and Daily - Dayname
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_week_dayname',
            [
                'label' => __('Week and Daily Dayname Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_height',
            [
                'label' => __('Dayname Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 42
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderTop_px',
            [
                'label' => __('Border Top Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderTop_style',
            [
                'label' => __('Border Top Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderTop_color',
            [
                'label' => __('Border Top Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderBottom_px',
            [
                'label' => __('Border Bottom Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderBottom_style',
            [
                'label' => __('Border Bottom Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderBottom_color',
            [
                'label' => __('Border Bottom Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderLeft_px',
            [
                'label' => __('Border Left Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderLeft_style',
            [
                'label' => __('Border Left Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_borderLeft_color',
            [
                'label' => __('Border Left Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_paddingLeft',
            [
                'label' => __('Padding Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 25,
                'step' => 1,
                'default' => 10
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_dayname_textAlign',
            [
                'label' => __('Text Align', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
            ]
        );
        $this->add_control(
            'advanced_style_week_today_color',
            [
                'label' => __('Today Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#333',
                'default' => '#333',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_pastDay_color',
            [
                'label' => __('Pastday Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#bbb',
                'default' => '#bbb',
                'alpha' => true
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Week and Daily - VPanel Splitter
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_week_vpanelsplitter',
            [
                'label' => __('Week and Daily VPanel Splitter Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_week_vpanelSplitter_border_px',
            [
                'label' => __('Border Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_vpanelSplitter_border_style',
            [
                'label' => __('Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_vpanelSplitter_border_color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_vpanelSplitter_height',
            [
                'label' => __('Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Week and Daily - Daygrid
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_week_daygrid',
            [
                'label' => __('Week and Daily Daygrid Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_week_daygrid_borderRight_px',
            [
                'label' => __('Border Right Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_daygrid_borderRight_style',
            [
                'label' => __('Border Right Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_daygrid_borderRight_color',
            [
                'label' => __('Border Right Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_daygrid_backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_daygridLeft_width',
            [
                'label' => __('Left Width [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 72
            ]
        );
        $this->add_control(
            'advanced_style_week_daygridLeft_backgroundColor',
            [
                'label' => __('Left Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_daygridLeft_paddingRight',
            [
                'label' => __('Left Padding Right [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 8
            ]
        );
        $this->add_control(
            'advanced_style_week_daygridLeft_borderRight_px',
            [
                'label' => __('Left Border Right Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_daygridLeft_borderRight_style',
            [
                'label' => __('Left Border Right Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_daygridLeft_borderRight_color',
            [
                'label' => __('Left Border Right Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_today_backgroundColor',
            [
                'label' => __('Today Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(81, 92, 230, 0.05)',
                'default' => 'rgba(81, 92, 230, 0.05)',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_weekend_backgroundColor',
            [
                'label' => __('Weekend Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Week and Daily - Timegrid
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_week_timegrid',
            [
                'label' => __('Week and Daily Timegrid Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridLeft_width',
            [
                'label' => __('Left Width [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 72
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridLeft_backgroundColor',
            [
                'label' => __('Left Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridLeft_borderRight_px',
            [
                'label' => __('Left Border Right Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridLeft_borderRight_style',
            [
                'label' => __('Left Border Right Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridLeft_borderRight_color',
            [
                'label' => __('Left Border Right Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridLeft_fontSize',
            [
                'label' => __('Left Font Size [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 11
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridLeftTimezoneLabel_height',
            [
                'label' => __('Left Timezone Label Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 40
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridLeftAdditionalTimezone_backgroundColor',
            [
                'label' => __('Left Additional Timezone Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#fff',
                'default' => '#fff',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridOneHour_height',
            [
                'label' => __('1-Hour Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 52
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridHalfHour_height',
            [
                'label' => __('0.5-Hour Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 26
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridHalfHour_borderBottom_px',
            [
                'label' => __('0.5-Hour Border Bottom Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 0
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridHalfHour_borderBottom_style',
            [
                'label' => __('0.5-Hour Border Bottom Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridHalfHour_borderBottom_color',
            [
                'label' => __('0.5-Hour Border Bottom Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'transparent',
                'default' => 'transparent',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridHorizontalLine_borderBottom_px',
            [
                'label' => __('Horizontal Line Border Bottom Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridHorizontalLine_borderBottom_style',
            [
                'label' => __('Horizontal Line Border Bottom Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_common_timegridHorizontalLine_borderBottom_color',
            [
                'label' => __('Horizontal Line Border Bottom Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_timegrid_paddingRight',
            [
                'label' => __('Padding Right [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 8
            ]
        );
        $this->add_control(
            'advanced_style_week_timegrid_borderRight_px',
            [
                'label' => __('Border Right Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_timegrid_borderRight_style',
            [
                'label' => __('Border Right Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_timegrid_borderRight_color',
            [
                'label' => __('Border Right Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridSchedule_borderRadius',
            [
                'label' => __('Schedule Border Radius [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control(
            'advanced_style_week_timegridSchedule_paddingLeft',
            [
                'label' => __('Schedule Padding Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Week and Daily - Time
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_week_time',
            [
                'label' => __('Week and Daily Time Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTime_color',
            [
                'label' => __('Current Time Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTime_fontSize',
            [
                'label' => __('Current Time Font Size [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 11
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTime_fontWeight',
            [
                'label' => __('Current Time Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'normal',
                'default' => 'normal'
            ]
        );
        $this->add_control(
            'advanced_style_week_pastTime_color',
            [
                'label' => __('Past Time Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#bbb',
                'default' => '#bbb',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_pastTime_fontWeight',
            [
                'label' => __('Past Time Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'normal',
                'default' => 'normal'
            ]
        );
        $this->add_control(
            'advanced_style_week_futureTime_color',
            [
                'label' => __('Future Time Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#bbb',
                'default' => '#bbb',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_futureTime_fontWeight',
            [
                'label' => __('Future Time Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'normal',
                'default' => 'normal'
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLinePast_border_px',
            [
                'label' => __('Current Time Line Past Border Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLinePast_border_style',
            [
                'label' => __('Current Time Line Past Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'dashed',
                'default' => 'dashed'
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLinePast_border_color',
            [
                'label' => __('Current Time Line Past Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLineBullet_backgroundColor',
            [
                'label' => __('Current Time Line Bullet Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLineToday_border_px',
            [
                'label' => __('Current Time Line Today Border Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLineToday_border_style',
            [
                'label' => __('Current Time Line Today Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLineToday_border_color',
            [
                'label' => __('Current Time Line Today Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLineFuture_border_px',
            [
                'label' => __('Current Time Line Future Border Size', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 16,
                'step' => 1,
                'default' => 0
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLineFuture_border_style',
            [
                'label' => __('Current Time Line Future Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced_style_week_currentTimeLineFuture_border_color',
            [
                'label' => __('Current Time Line Future Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'transparent',
                'default' => 'transparent',
                'alpha' => true
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Week and Daily - Creation Guide
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_week_creation_guide',
            [
                'label' => __('Week and Daily Creation Guide Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_week_creationGuide_color',
            [
                'label' => __('Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced_style_week_creationGuide_fontSize',
            [
                'label' => __('Font Size [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 11
            ]
        );
        $this->add_control(
            'advanced_style_week_creationGuide_fontWeight',
            [
                'label' => __('Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'bold',
                'default' => 'bold'
            ]
        );
        $this->end_controls_section();

        /*
         * Advanced Styling Configuration - Week and Daily - Schedule
         */
        $this->start_controls_section(
            'section_advanced_styling_configuration_week_schedule',
            [
                'label' => __('Week and Daily Schedule Styles', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'advanced_style_week_dayGridSchedule_borderRadius',
            [
                'label' => __('Border Radius [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control(
            'advanced_style_week_dayGridSchedule_height',
            [
                'label' => __('Height [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 24
            ]
        );
        $this->add_control(
            'advanced_style_week_dayGridSchedule_marginTop',
            [
                'label' => __('Margin Top [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control(
            'advanced_style_week_dayGridSchedule_marginLeft',
            [
                'label' => __('Margin Left [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 8
            ]
        );
        $this->add_control(
            'advanced_style_week_dayGridSchedule_marginRight',
            [
                'label' => __('Margin Right [px]', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 255,
                'step' => 1,
                'default' => 8
            ]
        );
        $this->end_controls_section();
    }

    /**
     * @inheritDoc
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        echo '<div class="' . $this->get_name() . '-elementor-widget">';
        echo do_shortcode($this->getShortCode('micro_erp_planning_calendar', $settings));
        echo '</div>';
    }

    /**
     * @inheritDoc
     */
    protected function _content_template()
    {
        $settings = $this->get_frontend_settings_keys();

        echo '<div class="' . $this->get_name() . '-elementor-widget">';
        echo do_shortcode($this->getShortCodePreview('micro_erp_planning_calendar', $settings));
        echo '</div>';
    }
}