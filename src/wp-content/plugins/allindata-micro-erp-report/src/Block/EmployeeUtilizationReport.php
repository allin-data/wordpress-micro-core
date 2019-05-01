<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Report\Block;

use AllInData\MicroErp\Core\Block\AbstractBlock;
use AllInData\MicroErp\Report\Helper\UtilizationReportHelper;
use DateInterval;
use DateTime;
use Exception;
use function GuzzleHttp\Psr7\modify_request;

/**
 * Class EmployeeUtilizationReport
 * @package AllInData\MicroErp\Report\Block
 */
class EmployeeUtilizationReport extends AbstractBlock
{
    const SCOPE_LAST_MONTH = 'last-month';
    const SCOPE_CURRENT_MONTH = 'current-month';
    const SCOPE_NEXT_MONTH = 'next-month';
    const INTERVAL_DAY = 'interval-day';
    const INTERVAL_WEEK = 'interval-week';
    const INTERVAL_MONTH = 'interval-month';

    /**
     * @var UtilizationReportHelper
     */
    private $utilizationReportHelper;

    /**
     * EmployeeUtilizationReport constructor.
     * @param UtilizationReportHelper $utilizationReportHelper
     */
    public function __construct(UtilizationReportHelper $utilizationReportHelper)
    {
        $this->utilizationReportHelper = $utilizationReportHelper;
    }

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
    public function getReportId(): string
    {
        return (string)$this->getAttribute('report_id');
    }

    /**
     * @return string
     */
    public function getUtilizationLabelByScope(): string
    {
        switch ($this->getAttribute('scope')) {
            case static::SCOPE_LAST_MONTH:
                return __('Last month', AID_MICRO_ERP_REPORT_TEXTDOMAIN);
            case static::SCOPE_CURRENT_MONTH:
                return __('Current month', AID_MICRO_ERP_REPORT_TEXTDOMAIN);
            case static::SCOPE_NEXT_MONTH:
                return __('Next month', AID_MICRO_ERP_REPORT_TEXTDOMAIN);
            default:
                return __('Your utilization', AID_MICRO_ERP_REPORT_TEXTDOMAIN);
        }
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getUtilizationValueByScope(): float
    {
        return round(100 * $this->utilizationReportHelper->getUtilizationFactorForUserInDateTimeRange(
            (int)get_current_user_id(),
            (int)$this->getAttribute('daily_working_hours'),
            $this->getUtilizationDateStart((string)$this->getAttribute('scope'), (string)$this->getAttribute('scope')),
            $this->getUtilizationDateEnd((string)$this->getAttribute('scope'), (string)$this->getAttribute('scope'))
        ), 2);
    }

    /**
     * @param string $scopeKey
     * @param string $intervalKey
     * @return DateTime
     * @throws Exception
     */
    private function getUtilizationDateStart(
        string $scopeKey = self::SCOPE_CURRENT_MONTH,
        string $intervalKey = self::INTERVAL_MONTH
    ): DateTime {
        $currentDate = new DateTime(date('Y-m-01'));
        $interval = new DateInterval($this->getDateInterval($intervalKey));
        switch ($scopeKey) {
            case static::SCOPE_LAST_MONTH:
                return $currentDate->sub($interval);
            case static::SCOPE_CURRENT_MONTH:
                return $currentDate;
            case static::SCOPE_NEXT_MONTH:
                return $currentDate->add($interval);
        }
        return $currentDate;
    }

    /**
     * @param string $scopeKey
     * @param string $intervalKey
     * @return DateTime
     * @throws Exception
     */
    private function getUtilizationDateEnd(
        string $scopeKey = self::SCOPE_CURRENT_MONTH,
        string $intervalKey = self::INTERVAL_MONTH
    ): DateTime {
        $currentDate = new DateTime(date('Y-m-t'));
        $interval = new DateInterval($this->getDateInterval($intervalKey));
        switch ($scopeKey) {
            case static::SCOPE_LAST_MONTH:
                return $currentDate->sub($interval);
            case static::SCOPE_CURRENT_MONTH:
                return $currentDate;
            case static::SCOPE_NEXT_MONTH:
                return $currentDate->add($interval);
        }
        return $currentDate;
    }

    /**
     * @param string $intervalKey
     * @return string
     */
    private function getDateInterval(string $intervalKey = self::INTERVAL_MONTH): string
    {
        switch ($intervalKey) {
            case static::INTERVAL_DAY:
                return 'P1D';
            case static::INTERVAL_WEEK:
                return 'P7D';
            case static::INTERVAL_MONTH:
                return 'P1M';
            default:
                return 'P1M';
        }
    }
}