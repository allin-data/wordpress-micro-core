<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms;

use AllInData\Dgr\Cms\Controller\Admin\CreateUser;
use AllInData\Dgr\Cms\Controller\Admin\UpdateUser;
use AllInData\Dgr\Cms\Model\Collection\User as UserCollection;
use AllInData\Dgr\Cms\Model\Factory\ElementorCmsAdminCategory;
use AllInData\Dgr\Cms\Model\Factory\ElementorCmsCategory;
use AllInData\Dgr\Cms\Model\Resource\User as UserResource;
use AllInData\Dgr\Cms\Model\Factory\User as UserFactory;
use AllInData\Dgr\Cms\Model\Validator\User as UserValidator;
use AllInData\Dgr\Cms\Model\User;
use AllInData\Dgr\Cms\Module\ElementorAdminCategory;
use AllInData\Dgr\Cms\Module\ElementorCategory;
use AllInData\Dgr\Cms\ShortCode\Admin\GridUser;
use AllInData\Dgr\Cms\ShortCode\UserOrganization;
use AllInData\Dgr\Cms\Widget\Elementor\FooBar;
use AllInData\Dgr\Cms\Widget\Elementor\Admin\ListOfUserAccounts;
use AllInData\Dgr\Cms\Widget\Elementor\UserOrganizationForm;
use AllInData\Dgr\Core\Controller\PluginControllerInterface;
use AllInData\Dgr\Core\Database\WordpressDatabase;
use AllInData\Dgr\Core\Module\PluginModuleInterface;
use AllInData\Dgr\Core\ShortCode\PluginShortCodeInterface;
use AllInData\Dgr\Core\Widget\ElementorWidgetInterface;
use Elementor\Elements_Manager;
use Elementor\Plugin as ElementorPlugin;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use Exception;

/**
 * Class PluginConfiguration
 * @package AllInData\Dgr\Cms
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
            AID_DGR_CMS_TEMPLATE_DIR,
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
                new ElementorCmsCategory(
                    \AllInData\Dgr\Cms\Model\ElementorCmsCategory::class
                ),
                $this->getElementorManager()
            ),
            new ElementorAdminCategory(
                new ElementorCmsAdminCategory(
                    \AllInData\Dgr\Cms\Model\ElementorCmsAdminCategory::class
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
            new UserOrganization(AID_DGR_CMS_TEMPLATE_DIR),
            new GridUser(
                AID_DGR_CMS_TEMPLATE_DIR,
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