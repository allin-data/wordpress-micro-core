<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource;

use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Database\WordpressDatabase;
use AllInData\MicroErp\Core\Model\GenericFactory;
use AllInData\MicroErp\Core\Model\GenericPagination;
use AllInData\MicroErp\Core\Model\GenericPaginationFilter;
use AllInData\MicroErp\Core\Model\GenericPaginationSorter;
use AllInData\MicroErp\Core\Model\GenericResource;
use AllInData\MicroErp\Core\Model\PaginationFilterFactory;
use AllInData\MicroErp\Core\Model\PaginationSorterFactory;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Mdm\Model\Capability\CapabilityInterface;
use AllInData\MicroErp\Resource\Controller\Admin\CreateResourceType;
use AllInData\MicroErp\Resource\Controller\Admin\UpdateResourceType;
use AllInData\MicroErp\Resource\Module\ElementorAdminCategory;
use AllInData\MicroErp\Resource\Model\ResourceType;
use AllInData\MicroErp\Resource\ShortCode\Admin\FormCreateResourceType;
use AllInData\MicroErp\Resource\ShortCode\Admin\GridResourceType;
use AllInData\MicroErp\Resource\Widget\Elementor\Admin\FormCreateNewResourceType;
use AllInData\MicroErp\Resource\Widget\Elementor\Admin\ListOfResourceTypes;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use Elementor\Elements_Manager;
use Elementor\Plugin as ElementorPlugin;
use Exception;

/**
 * Class PluginConfiguration
 * @package AllInData\MicroErp\Resource
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
            new FormCreateNewResourceType(),
            new ListOfResourceTypes()
        ];
    }

    /**
     * @return PluginModuleInterface[]
     */
    private function getPluginModules(): array
    {
        return [
            new ElementorAdminCategory(
                new GenericFactory(\AllInData\MicroErp\Resource\Model\ElementorResourceAdminCategory::class),
                $this->getElementorManager()
            )
        ];
    }

    /**
     * @return PluginControllerInterface[]
     */
    private function getPluginControllers(): array
    {
        return [
            new CreateResourceType($this->getResourceTypeResource()),
            new UpdateResourceType($this->getResourceTypeResource())
        ];
    }

    /**
     * @return PluginShortCodeInterface[]
     */
    private function getPluginShortCodes(): array
    {
        return [
            new GridResourceType(
                AID_MICRO_ERP_RESOURCE_TEMPLATE_DIR,
                new \AllInData\MicroErp\Resource\Block\Admin\GridResourceType(
                    $this->getResourceTypePagination()
                )
            ),
            new FormCreateResourceType(
                AID_MICRO_ERP_RESOURCE_TEMPLATE_DIR,
                new \AllInData\MicroErp\Resource\Block\Admin\FormCreateResourceType()
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
     * @return GenericFactory
     */
    private function getResourceTypeFactory(): GenericFactory
    {
        return new GenericFactory(
            ResourceType::class
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
     * @return GenericCollection
     */
    private function getResourceTypeCollection(): GenericCollection
    {
        return new GenericCollection(
            $this->getResourceTypeResource()
        );
    }

    /**
     * @return GenericPagination
     */
    private function getResourceTypePagination(): GenericPagination
    {
        return new GenericPagination(
            $this->getResourceTypeCollection(),
            new PaginationFilterFactory(GenericPaginationFilter::class),
            new PaginationSorterFactory(GenericPaginationSorter::class)
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

    /**
     * @return Elements_Manager
     */
    private function getElementorManager(): Elements_Manager
    {
        return ElementorPlugin::instance()->elements_manager;
    }
}