<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Report\Widget\Elementor;

use AllInData\MicroErp\Report\Model\ElementorReportCategory as ElementorCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;

/**
 * Class EmployeeUtilizationReport
 * @package AllInData\MicroErp\Report\Widget\Elementor
 */
class EmployeeUtilizationReport extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-micro-erp-report-employee-utilization';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('Employee Utilization Report', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-table';
    }

    /**
     * @inheritDoc
     */
    public function get_categories()
    {
        return [ElementorCategory::CATEGORY_NAME];
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
                'label' => __('Main Configuration', AID_MICRO_ERP_REPORT_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'id',
            [
                'label' => __('ID', AID_MICRO_ERP_REPORT_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter an unique report id', AID_MICRO_ERP_REPORT_TEXTDOMAIN),
                'default' => uniqid()
            ]
        );
        $this->add_control(
            'title',
            [
                'label' => __('Title', AID_MICRO_ERP_REPORT_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter the title', AID_MICRO_ERP_REPORT_TEXTDOMAIN),
            ]
        );
        $this->add_control(
            'scope',
            [
                'label' => __('Resource Type', AID_MICRO_ERP_REPORT_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'current-month' => __('Current Month', AID_MICRO_ERP_REPORT_TEXTDOMAIN)
                ]
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

        echo '<div class="'.$this->get_name().'-elementor-widget">';
        echo do_shortcode($this->getShortCode('micro_erp_report_employee_utilization', $settings));
        echo '</div>';
    }
}