<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Report\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;

/**
 * Class EmployeeUtilizationReport
 * @package AllInData\MicroErp\Report\Block
 */
class EmployeeUtilizationReport extends AbstractBlock
{
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return (string)$this->getAttribute('title');
    }
    /**
     * @return string
     */
    public function getUtilizationLabelByScope(): string
    {
        switch ($this->getAttribute('scope')) {
            case 'current-month':
                return __('Your utilization this month', AID_MICRO_ERP_REPORT_TEXTDOMAIN);
            default:
                return __('Your utilization', AID_MICRO_ERP_REPORT_TEXTDOMAIN);
        }
    }

    /**
     * @return float
     */
    public function getUtilizationValueByScope(): float
    {
        return 80.87;
    }
}