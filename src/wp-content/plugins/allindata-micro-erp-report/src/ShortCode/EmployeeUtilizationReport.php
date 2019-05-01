<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Report\ShortCode;

use AllInData\MicroErp\Core\ShortCode\AbstractShortCode;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;

/**
 * Class EmployeeUtilizationReport
 * @package AllInData\MicroErp\Report\ShortCode
 */
class EmployeeUtilizationReport extends AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var \AllInData\MicroErp\Report\Block\EmployeeUtilizationReport
     */
    private $block;

    /**
     * Calendar constructor.
     * @param string $templatePath
     * @param \AllInData\MicroErp\Report\Block\EmployeeUtilizationReport $block
     */
    public function __construct(
        string $templatePath,
        \AllInData\MicroErp\Report\Block\EmployeeUtilizationReport $block
    ) {
        parent::__construct($templatePath);
        $this->block = $block;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_shortcode('micro_erp_report_employee_utilization', [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if (is_admin() ||
            !is_user_logged_in()) {
            return '';
        }

        if (empty($attributes)) {
            $attributes = [];
        }
        $attributes = $this->prepareAttributes($attributes ?? [], [
            'report_id' => '',
            'title' => __('Utilization Report', AID_MICRO_ERP_MDM_TEXTDOMAIN),
            'scope' => \AllInData\MicroErp\Report\Block\EmployeeUtilizationReport::SCOPE_CURRENT_MONTH,
            'interval' => \AllInData\MicroErp\Report\Block\EmployeeUtilizationReport::INTERVAL_MONTH,
            'daily_working_hours' => 8
        ], $name);
        $block = clone $this->block;
        $block->setAttributes($attributes);

        $this->getTemplate('utilization-report', [
            'block' => $block
        ]);
    }
}