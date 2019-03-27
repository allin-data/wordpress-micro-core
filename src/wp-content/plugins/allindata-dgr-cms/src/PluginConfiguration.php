<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms;

use AllInData\Dgr\Cms\ShortCode\UserOrganization;
use AllInData\Dgr\Core\Controller\PluginControllerInterface;
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
    public function PluginApp() : Plugin
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
    private function getPluginControllers() : array
    {
        return [];
    }

    /**
     * @return PluginShortCodeInterface[]
     */
    private function getPluginShortCodes() : array
    {
        return [
            new UserOrganization(AID_DGR_CMS_TEMPLATE_DIR)
        ];
    }
}