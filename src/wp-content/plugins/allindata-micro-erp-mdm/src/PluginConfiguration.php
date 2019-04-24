<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm;

use AllInData\MicroErp\Core\Model\GenericPaginationFilter;
use AllInData\MicroErp\Core\Model\GenericPaginationSorter;
use AllInData\MicroErp\Core\Model\PaginationFilterFactory;
use AllInData\MicroErp\Core\Model\PaginationSorterFactory;
use AllInData\MicroErp\Mdm\Controller\Admin\CreateUser;
use AllInData\MicroErp\Mdm\Controller\Admin\UpdateUser;
use AllInData\MicroErp\Mdm\Model\Capability\CapabilityInterface;
use AllInData\MicroErp\Mdm\Model\Collection\Pagination;
use AllInData\MicroErp\Mdm\Model\Collection\User as UserCollection;
use AllInData\MicroErp\Mdm\Model\Factory\ElementorMdmAdminCategory;
use AllInData\MicroErp\Mdm\Model\Factory\ElementorMdmCategory;
use AllInData\MicroErp\Mdm\Model\Resource\User as UserResource;
use AllInData\MicroErp\Mdm\Model\Factory\User as UserFactory;
use AllInData\MicroErp\Mdm\Model\Role\ManagerRole;
use AllInData\MicroErp\Mdm\Model\Role\OwnerRole;
use AllInData\MicroErp\Mdm\Model\Role\RoleInterface;
use AllInData\MicroErp\Mdm\Model\Role\UserRole;
use AllInData\MicroErp\Mdm\Model\Validator\User as UserValidator;
use AllInData\MicroErp\Mdm\Model\User;
use AllInData\MicroErp\Mdm\Module\ElementorAdminCategory;
use AllInData\MicroErp\Mdm\Module\ElementorCategory;
use AllInData\MicroErp\Mdm\ShortCode\Admin\FormCreateUser;
use AllInData\MicroErp\Mdm\ShortCode\Admin\GridUser;
use AllInData\MicroErp\Mdm\ShortCode\UserProfile;
use AllInData\MicroErp\Mdm\Widget\Elementor\Admin\FormCreateNewUser;
use AllInData\MicroErp\Mdm\Widget\Elementor\Admin\ListOfUserAccounts;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Database\WordpressDatabase;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Mdm\Widget\Elementor\UserProfileForm;
use Elementor\Elements_Manager;
use Elementor\Plugin as ElementorPlugin;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use Exception;

/**
 * Class PluginConfiguration
 * @package AllInData\MicroErp\Mdm
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
            AID_MICRO_ERP_MDM_TEMPLATE_DIR,
            $this->getPluginModules(),
            $this->getPluginControllers(),
            $this->getPluginShortCodes(),
            $this->getPluginWidgets(),
            $this->getPluginRoles(),
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
            new UserProfileForm(),
            new ListOfUserAccounts(),
            new FormCreateNewUser()
        ];
    }

    /**
     * @return PluginModuleInterface[]
     */
    private function getPluginModules(): array
    {
        return [
            new ElementorCategory(
                new ElementorMdmCategory(
                    \AllInData\MicroErp\Mdm\Model\ElementorMdmCategory::class
                ),
                $this->getElementorManager()
            ),
            new ElementorAdminCategory(
                new ElementorMdmAdminCategory(
                    \AllInData\MicroErp\Mdm\Model\ElementorMdmAdminCategory::class
                ),
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
            new CreateUser(
                $this->getUserValidator(),
                $this->getUserResource()
            ),
            new UpdateUser(
                $this->getUserValidator(),
                $this->getUserResource()
            ),
            new \AllInData\MicroErp\Mdm\Controller\UpdateUser(
                $this->getUserValidator(),
                $this->getUserResource()
            )
        ];
    }

    /**
     * @return PluginShortCodeInterface[]
     */
    private function getPluginShortCodes(): array
    {
        return [
            new UserProfile(
                AID_MICRO_ERP_MDM_TEMPLATE_DIR,
                new Block\UserProfile($this->getUserResource())
            ),
            new GridUser(
                AID_MICRO_ERP_MDM_TEMPLATE_DIR,
                new Block\Admin\GridUser($this->getUserPagination())
            ),
            new FormCreateUser(
                AID_MICRO_ERP_MDM_TEMPLATE_DIR,
                new Block\Admin\FormCreateUser()
            )
        ];
    }

    /**
     * @return RoleInterface[]
     */
    private function getPluginRoles(): array
    {
        return [
            new UserRole([
                'main'
            ]),
            new ManagerRole([
                'main'
            ]),
            new OwnerRole([
                'main'
            ])
        ];
    }

    /**
     * @return CapabilityInterface[]
     */
    private function getPluginCapabilities(): array
    {
        return [
            new \AllInData\MicroErp\Mdm\Model\Capability\UpdateOrganization([
                OwnerRole::ROLE_LEVEL
            ])
        ];
    }

    /**
     * @return UserValidator
     */
    private function getUserValidator(): UserValidator
    {
        return new UserValidator(
            User::class
        );
    }

    /**
     * @return UserFactory
     */
    private function getUserFactory(): UserFactory
    {
        return new UserFactory(
            User::class
        );
    }

    /**
     * @return UserResource
     */
    private function getUserResource(): UserResource
    {
        return new UserResource(
            $this->getWordpressDatabase(),
            UserResource::ENTITY_NAME,
            $this->getUserFactory()
        );
    }

    /**
     * @return UserCollection
     */
    private function getUserCollection(): UserCollection
    {
        return new UserCollection(
            $this->getUserResource()
        );
    }

    /**
     * @return Pagination
     */
    private function getUserPagination(): Pagination
    {
        return new Pagination(
            $this->getUserCollection(),
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