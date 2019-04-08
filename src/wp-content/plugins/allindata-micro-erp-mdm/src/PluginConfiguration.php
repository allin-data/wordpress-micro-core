<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm;

use AllInData\MicroErp\Mdm\Controller\Admin\CreateUser;
use AllInData\MicroErp\Mdm\Controller\Admin\UpdateUser;
use AllInData\MicroErp\Mdm\Model\Collection\User as UserCollection;
use AllInData\MicroErp\Mdm\Model\Factory\ElementorMdmAdminCategory;
use AllInData\MicroErp\Mdm\Model\Factory\ElementorMdmCategory;
use AllInData\MicroErp\Mdm\Model\Resource\User as UserResource;
use AllInData\MicroErp\Mdm\Model\Factory\User as UserFactory;
use AllInData\MicroErp\Mdm\Model\Validator\User as UserValidator;
use AllInData\MicroErp\Mdm\Model\User;
use AllInData\MicroErp\Mdm\Module\ElementorAdminCategory;
use AllInData\MicroErp\Mdm\Module\ElementorCategory;
use AllInData\MicroErp\Mdm\ShortCode\Admin\GridUser;
use AllInData\MicroErp\Mdm\ShortCode\UserOrganization;
use AllInData\MicroErp\Mdm\Widget\Elementor\FooBar;
use AllInData\MicroErp\Mdm\Widget\Elementor\Admin\ListOfUserAccounts;
use AllInData\MicroErp\Mdm\Widget\Elementor\UserOrganizationForm;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Database\WordpressDatabase;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
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
            new FooBar(),
            new ListOfUserAccounts(),
            new UserOrganizationForm()
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
            )
        ];
    }

    /**
     * @return PluginShortCodeInterface[]
     */
    private function getPluginShortCodes(): array
    {
        return [
            new UserOrganization(AID_MICRO_ERP_MDM_TEMPLATE_DIR),
            new GridUser(
                AID_MICRO_ERP_MDM_TEMPLATE_DIR,
                new Block\Admin\GridUser($this->getUserCollection())
            )
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