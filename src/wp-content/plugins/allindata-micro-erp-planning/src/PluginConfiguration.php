<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning;

use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Database\WordpressDatabase;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Planning\Controller\CreateSchedule;
use AllInData\MicroErp\Planning\Controller\DeleteSchedule;
use AllInData\MicroErp\Planning\Controller\UpdateSchedule;
use AllInData\MicroErp\Planning\Model\Factory\ScheduleMeta;
use AllInData\MicroErp\Planning\Model\Factory\ScheduleMetaCreator;
use AllInData\MicroErp\Planning\Model\Schedule;
use AllInData\MicroErp\Planning\ShortCode\Calendar;
use AllInData\MicroErp\Planning\Model\Collection\Schedule as ScheduleCollection;
use AllInData\MicroErp\Planning\Model\Resource\Schedule as ScheduleResource;
use AllInData\MicroErp\Planning\Model\Factory\Schedule as ScheduleFactory;
use AllInData\MicroErp\Planning\Model\Validator\Schedule as ScheduleValidator;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use Exception;

/**
 * Class PluginConfiguration
 * @package AllInData\MicroErp\Planning
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
            $this->getPluginWidgets()
        );
    }

    /**
     * @return ElementorWidgetInterface[]
     * @throws Exception
     */
    private function getPluginWidgets(): array
    {
        return [
            new \AllInData\MicroErp\Planning\Widget\Elementor\Calendar()
        ];
    }

    /**
     * @return PluginModuleInterface[]
     */
    private function getPluginModules(): array
    {
        return [];
    }

    /**
     * @return PluginControllerInterface[]
     */
    private function getPluginControllers(): array
    {
        return [
            new CreateSchedule(
                $this->getScheduleValidator(),
                $this->getScheduleResource()
            ),
            new UpdateSchedule(
                $this->getScheduleValidator(),
                $this->getScheduleResource()
            ),
            new DeleteSchedule(
                $this->getScheduleResource()
            )
        ];
    }

    /**
     * @return PluginShortCodeInterface[]
     */
    private function getPluginShortCodes(): array
    {
        return [
            new Calendar(
                AID_MICRO_ERP_PLANNING_TEMPLATE_DIR,
                new \AllInData\MicroErp\Planning\Block\Calendar(
                    $this->getScheduleCollection()
                )
            )
        ];
    }

    /**
     * @return ScheduleValidator
     */
    private function getScheduleValidator(): ScheduleValidator
    {
        return new ScheduleValidator(
            Schedule::class
        );
    }

    /**
     * @return ScheduleFactory
     */
    private function getScheduleFactory(): ScheduleFactory
    {
        return new ScheduleFactory(
            Schedule::class,
            new ScheduleMeta(
            \AllInData\MicroErp\Planning\Model\ScheduleMeta::class,
                new ScheduleMetaCreator(\AllInData\MicroErp\Planning\Model\ScheduleMetaCreator::class)
            )
        );
    }

    /**
     * @return ScheduleResource
     */
    private function getScheduleResource(): ScheduleResource
    {
        return new ScheduleResource(
            $this->getWordpressDatabase(),
            ScheduleResource::ENTITY_NAME,
            $this->getScheduleFactory()
        );
    }

    /**
     * @return ScheduleCollection
     */
    private function getScheduleCollection(): ScheduleCollection
    {
        return new ScheduleCollection(
            $this->getScheduleResource()
        );
    }

    /**
     * @return WordpressDatabase
     */
    private function getWordpressDatabase(): WordpressDatabase
    {
        global $wpdb;
        return new WordpressDatabase($wpdb);
    }
}