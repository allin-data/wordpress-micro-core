<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Widget\Elementor;

use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;
use AllInData\MicroErp\Mdm\Model\ElementorMdmCategory;

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
                'label' => __('Main Configuration', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'id',
            [
                'label' => __('ID', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter an unique calendar id', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'default' => uniqid()
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => __('Title', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter the calendar title', AID_MICRO_ERP_MDM_TEXTDOMAIN),
            ]
        );

        $this->add_control(
            'default-view',
            [
                'label' => __('Default View', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'month' => __('Month', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                    'week' => __('Week', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                    'day' => __('Day', AID_MICRO_ERP_MDM_TEXTDOMAIN),
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
                'label' => __('Labels (General)', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label-schedule_monthly',
            [
                'label' => __('Monthly Schedule', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Monthly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Monthly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-schedule_weekly',
            [
                'label' => __('Weekly Schedule', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Weekly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Weekly Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-schedule_daily',
            [
                'label' => __('Daily Schedule', AID_MICRO_ERP_MDM_TEXTDOMAIN),
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
                'label' => __('Labels (Calendar)', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label-milestone',
            [
                'label' => __('Milestone', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Milestone', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Milestone', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-task',
            [
                'label' => __('Task', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Task', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Task', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-all_day',
            [
                'label' => __('All Day', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('All Day', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('All Day', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-new_schedule',
            [
                'label' => __('New Schedule', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('New Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('New Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-more_events',
            [
                'label' => __('See %1$s more events', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('See %1$s more events', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('See %1$s more events', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-going_time',
            [
                'label' => __('GoingTime', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('GoingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('GoingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-coming_time',
            [
                'label' => __('ComingTime', AID_MICRO_ERP_MDM_TEXTDOMAIN),
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
                'label' => __('Labels (Weekdays)', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'label-sunday',
            [
                'label' => __('Sunday', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Sunday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Sunday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-monday',
            [
                'label' => __('Monday', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Monday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Monday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-tuesday',
            [
                'label' => __('Tuesday', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Tuesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Tuesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-wednesday',
            [
                'label' => __('Wednesday', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Wednesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Wednesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-thursday',
            [
                'label' => __('Thursday', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Thursday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Thursday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-friday',
            [
                'label' => __('Friday', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Friday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Friday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
            ]
        );
        $this->add_control(
            'label-saturday',
            [
                'label' => __('Saturday', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Saturday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN),
                'default' => __('Saturday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN)
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
}