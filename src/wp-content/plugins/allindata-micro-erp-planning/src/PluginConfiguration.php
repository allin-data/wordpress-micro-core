<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning;

use AllInData\MicroErp\Core\Model\GenericOwnedCollection;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Database\WordpressDatabase;
use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Core\Model\GenericFactory;
use AllInData\MicroErp\Core\Model\GenericOwnedResource;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Mdm\Model\Capability\CapabilityInterface;
use AllInData\MicroErp\Mdm\Model\Role\ManagerRole;
use AllInData\MicroErp\Mdm\Model\Role\OwnerRole;
use AllInData\MicroErp\Mdm\Model\Role\UserRole;
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
use AllInData\MicroErp\Resource\Model\Resource;
use AllInData\MicroErp\Resource\Model\ResourceAttributeValue;
use AllInData\MicroErp\Resource\Model\ResourceType;
use AllInData\MicroErp\Resource\Model\ResourceTypeAttribute;
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
                    $this->getScheduleCollection(),
                    $this->getResourceTypeCollection(),
                    $this->getResourceCollection(),
                    $this->getResourceTypeAttributeCollection(),
                    $this->getResourceAttributeValueCollection()
                )
            )
        ];
    }

    /**
     * @return CapabilityInterface[]
     */
    private function getPluginCapabilities(): array
    {
        return [
            new \AllInData\MicroErp\Planning\Model\Capability\CreateSchedule([
                UserRole::ROLE_LEVEL,
                ManagerRole::ROLE_LEVEL,
                OwnerRole::ROLE_LEVEL
            ]),
            new \AllInData\MicroErp\Planning\Model\Capability\UpdateSchedule([
                UserRole::ROLE_LEVEL,
                ManagerRole::ROLE_LEVEL,
                OwnerRole::ROLE_LEVEL
            ]),
            new \AllInData\MicroErp\Planning\Model\Capability\DeleteSchedule([
                UserRole::ROLE_LEVEL,
                ManagerRole::ROLE_LEVEL,
                OwnerRole::ROLE_LEVEL
            ]),
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
     * @return GenericFactory
     */
    private function getResourceFactory(): GenericFactory
    {
        return new GenericFactory(
            Resource::class
        );
    }

    /**
     * @return \AllInData\MicroErp\Resource\Model\Factory\ResourceType
     */
    private function getResourceTypeFactory(): \AllInData\MicroErp\Resource\Model\Factory\ResourceType
    {
        return new \AllInData\MicroErp\Resource\Model\Factory\ResourceType(
            ResourceType::class,
            $this->getResourceTypeAttributeFactory()
        );
    }

    /**
     * @return GenericFactory
     */
    private function getResourceTypeAttributeFactory(): GenericFactory
    {
        return new GenericFactory(
            ResourceTypeAttribute::class
        );
    }

    /**
     * @return GenericFactory
     */
    private function getResourceAttributeValueFactory(): GenericFactory
    {
        return new GenericFactory(
            ResourceAttributeValue::class
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
     * @return GenericOwnedResource
     */
    private function getResourceResource(): GenericOwnedResource
    {
        return new GenericOwnedResource(
            $this->getWordpressDatabase(),
            'resource',
            $this->getResourceFactory()
        );
    }

    /**
     * @return GenericResource
     */
    private function getResourceTypeResource(): GenericResource
    {
        return new GenericResource(
            $this->getWordpressDatabase(),
            'resource_type',
            $this->getResourceTypeFactory()
        );
    }

    /**
     * @return GenericResource
     */
    private function getResourceTypeAttributeResource(): GenericResource
    {
        return new GenericResource(
            $this->getWordpressDatabase(),
            'restype_attribute',
            $this->getResourceTypeAttributeFactory()
        );
    }

    /**
     * @return GenericOwnedResource
     */
    private function getResourceAttributeValueResource(): GenericOwnedResource
    {
        return new GenericOwnedResource(
            $this->getWordpressDatabase(),
            'resattribute_value',
            $this->getResourceAttributeValueFactory()
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
     * @return GenericOwnedCollection
     */
    private function getResourceCollection(): GenericOwnedCollection
    {
        return new GenericOwnedCollection(
            $this->getResourceResource()
        );
    }

    /**
     * @return GenericCollection
     */
    private function getResourceTypeCollection(): GenericCollection
    {
        return new GenericCollection(
            $this->getResourceTypeResource()
        );
    }

    /**
     * @return \AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute
     */
    private function getResourceTypeAttributeCollection(): \AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute
    {
        return new \AllInData\MicroErp\Resource\Model\Collection\ResourceTypeAttribute(
            $this->getResourceTypeAttributeResource()
        );
    }

    /**
     * @return GenericOwnedCollection
     */
    private function getResourceAttributeValueCollection(): GenericOwnedCollection
    {
        return new GenericOwnedCollection(
            $this->getResourceAttributeValueResource()
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