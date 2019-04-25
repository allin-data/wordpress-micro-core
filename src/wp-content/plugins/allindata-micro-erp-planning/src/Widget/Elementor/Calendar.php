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
            'id',
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
            'advanced-style_common.border.px',
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
            'advanced-style_common.border.style',
            [
                'label' => __('Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_common.border.color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e',
                'default' => '#e5e5e',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_common.backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#fff',
                'default' => '#fff',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_common.holiday.color',
            [
                'label' => __('Holiday Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#ff4040',
                'default' => '#ff4040',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_common.saturday.color',
            [
                'label' => __('Saturday Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#333',
                'default' => '#333',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_common.dayname.color',
            [
                'label' => __('Dayname Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#333',
                'default' => '#333',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_common.today.color',
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
            'advanced-style_common.creationGuide.backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(81, 92, 230, 0.05)',
                'default' => 'rgba(81, 92, 230, 0.05)',
                'alpha' => true
            ]
        );

        $this->add_control(
            'advanced-style_common.creationGuide.border.px',
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
            'advanced-style_common.creationGuide.border.style',
            [
                'label' => __('Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_common.creationGuide.border.color',
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
            'advanced-style_month.dayname.height',
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
            'advanced-style_month.dayname.borderLeft.px',
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
            'advanced-style_month.dayname.borderLeft.style',
            [
                'label' => __('Border Left Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_month.dayname.borderLeft.color',
            [
                'label' => __('Border Left Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.dayname.paddingLeft',
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
            'advanced-style_month.dayname.paddingRight',
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
            'advanced-style_month.dayname.backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.dayname.fontSize',
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
            'advanced-style_month.dayname.fontWeight',
            [
                'label' => __('Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'normal',
                'default' => 'normal'
            ]
        );
        $this->add_control(
            'advanced-style_month.dayname.textAlign',
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
            'advanced-style_month.holidayExceptThisMonth.color',
            [
                'label' => __('Holiday Except This Month Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(255, 64, 64, 0.4)',
                'default' => 'rgba(255, 64, 64, 0.4)',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.dayExceptThisMonth.color',
            [
                'label' => __('Day Except This Month Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(51, 51, 51, 0.4)',
                'default' => 'rgba(51, 51, 51, 0.4)',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.weekend.backgroundColor',
            [
                'label' => __('Weekend Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.day.fontSize',
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
            'advanced-style_month.schedule.borderRadius',
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
            'advanced-style_month.schedule.height',
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
            'advanced-style_month.schedule.marginTop',
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
            'advanced-style_month.schedule.marginLeft',
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
            'advanced-style_month.schedule.marginRight',
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
            'advanced-style_month.moreView.border.px',
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
            'advanced-style_month.moreView.border.style',
            [
                'label' => __('Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_month.moreView.border.color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#d5d5d5',
                'default' => '#d5d5d5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.moreView.boxShadow.px.top',
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
            'advanced-style_month.moreView.boxShadow.px.right',
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
            'advanced-style_month.moreView.boxShadow.px.bottom',
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
            'advanced-style_month.moreView.boxShadow.px.left',
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
            'advanced-style_month.moreView.boxShadow.color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(0, 0, 0, 0.1)',
                'default' => 'rgba(0, 0, 0, 0.1)',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.moreView.backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#fff',
                'default' => '#fff',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.moreView.paddingBottom',
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
            'advanced-style_month.moreViewTitle.height',
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
            'advanced-style_month.moreViewTitle.marginBottom',
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
            'advanced-style_month.moreViewTitle.backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.moreViewTitle.borderBottom.px',
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
            'advanced-style_month.moreViewTitle.borderBottom.style',
            [
                'label' => __('Title Border Bottom Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_month.moreViewTitle.borderBottom.color',
            [
                'label' => __('Title Border Bottom Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'transparent',
                'default' => 'transparent',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_month.moreViewTitle.padding.px.top',
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
            'advanced-style_month.moreViewTitle.padding.px.right',
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
            'advanced-style_month.moreViewTitle.padding.px.bottom',
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
            'advanced-style_month.moreViewTitle.padding.px.left',
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
            'advanced-style_month.moreViewList.padding.px.top',
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
            'advanced-style_month.moreViewList.padding.px.right',
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
            'advanced-style_month.moreViewList.padding.px.bottom',
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
            'advanced-style_month.moreViewList.padding.px.left',
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
            'advanced-style_week.dayname.height',
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
            'advanced-style_week.dayname.borderTop.px',
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
            'advanced-style_week.dayname.borderTop.style',
            [
                'label' => __('Border Top Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.dayname.borderTop.color',
            [
                'label' => __('Border Top Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.dayname.borderBottom.px',
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
            'advanced-style_week.dayname.borderBottom.style',
            [
                'label' => __('Border Bottom Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.dayname.borderBottom.color',
            [
                'label' => __('Border Bottom Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.dayname.borderLeft.px',
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
            'advanced-style_week.dayname.borderLeft.style',
            [
                'label' => __('Border Left Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.dayname.borderLeft.color',
            [
                'label' => __('Border Left Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.dayname.paddingLeft',
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
            'advanced-style_week.dayname.backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.dayname.textAlign',
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
            'advanced-style_week.today.color',
            [
                'label' => __('Today Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#333',
                'default' => '#333',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.pastDay.color',
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
            'advanced-style_week.vpanelSplitter.border.px',
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
            'advanced-style_week.vpanelSplitter.border.style',
            [
                'label' => __('Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.vpanelSplitter.border.color',
            [
                'label' => __('Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.vpanelSplitter.height',
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
            'advanced-style_week.daygrid.borderRight.px',
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
            'advanced-style_week.daygrid.borderRight.style',
            [
                'label' => __('Border Right Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.daygrid.borderRight.color',
            [
                'label' => __('Border Right Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.daygrid.backgroundColor',
            [
                'label' => __('Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.daygridLeft.width',
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
            'advanced-style_week.daygridLeft.backgroundColor',
            [
                'label' => __('Left Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.daygridLeft.paddingRight',
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
            'advanced-style_week.daygridLeft.borderRight.px',
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
            'advanced-style_week.daygridLeft.borderRight.style',
            [
                'label' => __('Left Border Right Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.daygridLeft.borderRight.color',
            [
                'label' => __('Left Border Right Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.today.backgroundColor',
            [
                'label' => __('Today Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'rgba(81, 92, 230, 0.05)',
                'default' => 'rgba(81, 92, 230, 0.05)',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.weekend.backgroundColor',
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
            'advanced-style_week.timegridLeft.width',
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
            'advanced-style_week.timegridLeft.backgroundColor',
            [
                'label' => __('Left Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'inherit',
                'default' => 'inherit',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_common.timegridLeft.borderRight.px',
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
            'advanced-style_common.timegridLeft.borderRight.style',
            [
                'label' => __('Left Border Right Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_common.timegridLeft.borderRight.color',
            [
                'label' => __('Left Border Right Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.timegridLeft.fontSize',
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
            'advanced-style_week.timegridLeftTimezoneLabel.height',
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
            'advanced-style_week.timegridLeftAdditionalTimezone.backgroundColor',
            [
                'label' => __('Left Additional Timezone Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#fff',
                'default' => '#fff',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.timegridOneHour.height',
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
            'advanced-style_week.timegridHalfHour.height',
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
            'advanced-style_common.timegridHalfHour.borderBottom.px',
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
            'advanced-style_common.timegridHalfHour.borderBottom.style',
            [
                'label' => __('0.5-Hour Border Bottom Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_common.timegridHalfHour.borderBottom.color',
            [
                'label' => __('0.5-Hour Border Bottom Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => 'transparent',
                'default' => 'transparent',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_common.timegridHorizontalLine.borderBottom.px',
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
            'advanced-style_common.timegridHorizontalLine.borderBottom.style',
            [
                'label' => __('Horizontal Line Border Bottom Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_common.timegridHorizontalLine.borderBottom.color',
            [
                'label' => __('Horizontal Line Border Bottom Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.timegrid.paddingRight',
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
            'advanced-style_week.timegrid.borderRight.px',
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
            'advanced-style_week.timegrid.borderRight.style',
            [
                'label' => __('Border Right Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.timegrid.borderRight.color',
            [
                'label' => __('Border Right Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#e5e5e5',
                'default' => '#e5e5e5',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.timegridSchedule.borderRadius',
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
            'advanced-style_week.timegridSchedule.paddingLeft',
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
            'advanced-style_week.currentTime.color',
            [
                'label' => __('Current Time Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.currentTime.fontSize',
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
            'advanced-style_week.currentTime.fontWeight',
            [
                'label' => __('Current Time Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'normal',
                'default' => 'normal'
            ]
        );
        $this->add_control(
            'advanced-style_week.pastTime.color',
            [
                'label' => __('Past Time Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#bbb',
                'default' => '#bbb',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.pastTime.fontWeight',
            [
                'label' => __('Past Time Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'normal',
                'default' => 'normal'
            ]
        );
        $this->add_control(
            'advanced-style_week.futureTime.color',
            [
                'label' => __('Future Time Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#bbb',
                'default' => '#bbb',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.futureTime.fontWeight',
            [
                'label' => __('Future Time Font Weight', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'normal',
                'default' => 'normal'
            ]
        );
        $this->add_control(
            'advanced-style_week.currentTimeLinePast.border.px',
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
            'advanced-style_week.currentTimeLinePast.border.style',
            [
                'label' => __('Current Time Line Past Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'dashed',
                'default' => 'dashed'
            ]
        );
        $this->add_control(
            'advanced-style_week.currentTimeLinePast.border.color',
            [
                'label' => __('Current Time Line Past Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.currentTimeLineBullet.backgroundColor',
            [
                'label' => __('Current Time Line Bullet Background Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.currentTimeLineToday.border.px',
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
            'advanced-style_week.currentTimeLineToday.border.style',
            [
                'label' => __('Current Time Line Today Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.currentTimeLineToday.border.color',
            [
                'label' => __('Current Time Line Today Border Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.currentTimeLineFuture.border.px',
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
            'advanced-style_week.currentTimeLineFuture.border.style',
            [
                'label' => __('Current Time Line Future Border Style', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'solid',
                'default' => 'solid'
            ]
        );
        $this->add_control(
            'advanced-style_week.currentTimeLineFuture.border.color',
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
            'advanced-style_week.creationGuide.color',
            [
                'label' => __('Color', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::COLOR,
                'placeholder' => '#515ce6',
                'default' => '#515ce6',
                'alpha' => true
            ]
        );
        $this->add_control(
            'advanced-style_week.creationGuide.fontSize',
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
            'advanced-style_week.creationGuide.fontWeight',
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
            'advanced-style_week.dayGridSchedule.borderRadius',
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
            'advanced-style_week.dayGridSchedule.height',
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
            'advanced-style_week.dayGridSchedule.marginTop',
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
            'advanced-style_week.dayGridSchedule.marginLeft',
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
            'advanced-style_week.dayGridSchedule.marginRight',
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

    protected function _content_template()
    {
        ?>
        <div class="wrapper"> ...</div>
        <?php
    }
}