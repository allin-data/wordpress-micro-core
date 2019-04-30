<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Report;

use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Model\GenericFactory;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Mdm\Model\Capability\CapabilityInterface;
use AllInData\MicroErp\Report\Module\ElementorCategory;
use AllInData\MicroErp\Report\Widget\Elementor\EmployeeUtilizationReport;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use Elementor\Elements_Manager;
use Elementor\Plugin as ElementorPlugin;
use Exception;

/**
 * Class PluginConfiguration
 * @package AllInData\MicroErp\Report
 * @Configuration
 */
class PluginConfiguration
{
    /**
     * @Bean
     */
    public function PluginApp(): Plugin
    {
        return new Plugin(
            AID_MICRO_ERP_PLANNING_TEMPLATE_DIR,
            $this->getPluginModules(),
            $this->getPluginControllers(),
            $this->getPluginShortCodes(),
            $this->getPluginWidgets(),
            $this->getPluginCapabilities()
        );
    }

    /**
     * @return ElementorWidgetInterface[]
     * @throws Exception
     */
    private function getPluginWidgets(): array
    {
        return [
            new EmployeeUtilizationReport()
        ];
    }

    /**
     * @return PluginModuleInterface[]
     */
    private function getPluginModules(): array
    {
        return [
            new ElementorCategory(
                new GenericFactory(\AllInData\MicroErp\Report\Model\ElementorReportCategory::class),
                $this->getElementorManager()
            )
        ];
    }

    /**
     * @return PluginControllerInterface[]
     */
    private function getPluginControllers(): array
    {
        return [];
    }

    /**
     * @return PluginShortCodeInterface[]
     */
    private function getPluginShortCodes(): array
    {
        return [
            new \AllInData\MicroErp\Report\ShortCode\EmployeeUtilizationReport(
                AID_MICRO_ERP_REPORT_TEMPLATE_DIR,
                new \AllInData\MicroErp\Report\Block\EmployeeUtilizationReport()
            )
        ];
    }

    /**
     * @return CapabilityInterface[]
     */
    private function getPluginCapabilities(): array
    {
        return [];
    }

    /**
     * @return Elements_Manager
     */
    private function getElementorManager(): Elements_Manager
    {
        return ElementorPlugin::instance()->elements_manager;
    }
}