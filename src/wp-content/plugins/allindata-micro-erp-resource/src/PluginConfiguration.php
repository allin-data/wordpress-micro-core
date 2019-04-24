<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource;

use AllInData\MicroErp\Auth\Model\GenericOwnedCollection;
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
use AllInData\MicroErp\Mdm\Model\Role\ManagerRole;
use AllInData\MicroErp\Mdm\Model\Role\OwnerRole;
use AllInData\MicroErp\Resource\Controller\Admin\CreateResourceType;
use AllInData\MicroErp\Resource\Controller\Admin\UpdateResourceType;
use AllInData\MicroErp\Resource\Model\Capability\CreateResource;
use AllInData\MicroErp\Resource\Model\Capability\DeleteResource;
use AllInData\MicroErp\Resource\Model\Capability\UpdateResource;
use AllInData\MicroErp\Resource\Model\Resource;
use AllInData\MicroErp\Resource\Module\ElementorAdminCategory;
use AllInData\MicroErp\Resource\Model\ResourceType;
use AllInData\MicroErp\Resource\Module\ElementorCategory;
use AllInData\MicroErp\Resource\ShortCode\Admin\FormCreateResourceType;
use AllInData\MicroErp\Resource\ShortCode\Admin\GridResourceType;
use AllInData\MicroErp\Resource\ShortCode\FormCreateResource;
use AllInData\MicroErp\Resource\ShortCode\GridResource;
use AllInData\MicroErp\Resource\Widget\Elementor\Admin\FormCreateNewResourceType;
use AllInData\MicroErp\Resource\Widget\Elementor\Admin\ListOfResourceTypes;
use AllInData\MicroErp\Resource\Widget\Elementor\FormCreateNewResource;
use AllInData\MicroErp\Resource\Widget\Elementor\ListOfResources;
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
        $formCreateNewResource = (new FormCreateNewResource())
            ->setResourceTypeCollection($this->getResourceTypeCollection());
        $listOfResources = (new ListOfResources())
            ->setResourceTypeCollection($this->getResourceTypeCollection());

        return [
            new FormCreateNewResourceType(),
            new ListOfResourceTypes(),
            $formCreateNewResource,
            $listOfResources
        ];
    }

    /**
     * @return PluginModuleInterface[]
     */
    private function getPluginModules(): array
    {
        return [
            new ElementorCategory(
                new GenericFactory(\AllInData\MicroErp\Resource\Model\ElementorResourceCategory::class),
                $this->getElementorManager()
            ),
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
            new UpdateResourceType($this->getResourceTypeResource()),
            new \AllInData\MicroErp\Resource\Controller\CreateResource(
                $this->getResourceResource(),
                $this->getResourceTypeResource()
            ),
            new \AllInData\MicroErp\Resource\Controller\UpdateResource(
                $this->getResourceResource()
            ),
            new \AllInData\MicroErp\Resource\Controller\DeleteResource(
                $this->getResourceResource()
            )
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
            ),
            new GridResource(
                AID_MICRO_ERP_RESOURCE_TEMPLATE_DIR,
                new \AllInData\MicroErp\Resource\Block\GridResource(
                    $this->getResourcePagination(),
                    $this->getResourceTypeResource()
                )
            ),
            new FormCreateResource(
                AID_MICRO_ERP_RESOURCE_TEMPLATE_DIR,
                new \AllInData\MicroErp\Resource\Block\FormCreateResource(
                    $this->getResourceTypeResource()
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
            new CreateResource([
                ManagerRole::ROLE_LEVEL,
                OwnerRole::ROLE_LEVEL
            ]),
            new UpdateResource([
                ManagerRole::ROLE_LEVEL,
                OwnerRole::ROLE_LEVEL
            ]),
            new DeleteResource([
                ManagerRole::ROLE_LEVEL,
                OwnerRole::ROLE_LEVEL
            ])
        ];
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
    private function getResourceResource(): GenericResource
    {
        return new GenericResource(
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
     * @return GenericPagination
     */
    private function getResourcePagination(): GenericPagination
    {
        return new GenericPagination(
            $this->getResourceCollection(),
            new PaginationFilterFactory(GenericPaginationFilter::class),
            new PaginationSorterFactory(GenericPaginationSorter::class)
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