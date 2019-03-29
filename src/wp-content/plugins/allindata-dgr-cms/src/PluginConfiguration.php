<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms;

use AllInData\Dgr\Cms\Model\Collection\User as UserCollection;
use AllInData\Dgr\Cms\Model\Resource\User as UserResource;
use AllInData\Dgr\Cms\Model\Factory\User as UserFactory;
use AllInData\Dgr\Cms\Model\User;
use AllInData\Dgr\Cms\ShortCode\Admin\GridUser;
use AllInData\Dgr\Cms\ShortCode\UserOrganization;
use AllInData\Dgr\Core\Controller\PluginControllerInterface;
use AllInData\Dgr\Core\Database\WordpressDatabase;
use AllInData\Dgr\Core\ShortCode\PluginShortCodeInterface;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;

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
            $this->getPluginControllers(),
            $this->getPluginShortCodes()
        );
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
            new UserOrganization(AID_DGR_CMS_TEMPLATE_DIR),
            new GridUser(
                AID_DGR_CMS_TEMPLATE_DIR,
                new Block\Admin\GridUser($this->getUserCollection())
            )
        ];
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
}