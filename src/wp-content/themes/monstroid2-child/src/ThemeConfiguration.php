<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme;

use AllInData\Dgr\Theme\Block\Navigation\Menu;
use AllInData\Dgr\Theme\Controller\Login;
use AllInData\Dgr\Theme\Controller\Logout;
use AllInData\Dgr\Theme\Controller\ThemeControllerInterface;
use AllInData\Dgr\Theme\Module\DisabledAdminBar;
use AllInData\Dgr\Theme\Module\ForceLogin;
use AllInData\Dgr\Theme\Module\ThemeModuleInterface;
use AllInData\Dgr\Theme\ShortCode\AdminMenu;
use AllInData\Dgr\Theme\ShortCode\LoginForm;
use AllInData\Dgr\Theme\ShortCode\ThemeShortCodeInterface;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;

/**
 * Class ThemeConfiguration
 * @package AllInData\Dgr\Theme
 * @Configuration
 */
class ThemeConfiguration
{
    /**
     * @Bean
     */
    public function Theme() : Theme
    {
        return new Theme(
            $this->getThemeModules(),
            $this->getThemeControllers(),
            $this->getThemeShortCodes()
        );
    }

    /**
     * @Bean
     * @return Menu
     */
    public function BlockNavigationMenu() : Menu
    {
        return new Menu();
    }

    /**
     * @return ThemeModuleInterface[]
     */
    private function getThemeModules() : array
    {
        return [
            new ForceLogin(),
            new DisabledAdminBar()
        ];
    }

    /**
     * @return ThemeControllerInterface[]
     */
    private function getThemeControllers() : array
    {
        return [
            new Login(),
            new Logout()
        ];
    }

    /**
     * @return ThemeShortCodeInterface[]
     */
    private function getThemeShortCodes() : array
    {
        return [
            new LoginForm(),
            new AdminMenu()
        ];
    }
}