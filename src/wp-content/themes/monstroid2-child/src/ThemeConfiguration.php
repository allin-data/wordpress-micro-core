<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme;

use AllInData\MicroErp\Theme\Block\Navigation\Menu;
use AllInData\MicroErp\Theme\Controller\Login;
use AllInData\MicroErp\Theme\Controller\Logout;
use AllInData\MicroErp\Theme\Controller\ThemeControllerInterface;
use AllInData\MicroErp\Theme\Module\DisabledAdminBar;
use AllInData\MicroErp\Theme\Module\ExtendMainMenuByAdminMenu;
use AllInData\MicroErp\Theme\Module\ForceLogin;
use AllInData\MicroErp\Theme\Module\ThemeModuleInterface;
use AllInData\MicroErp\Theme\ShortCode\LoginForm;
use AllInData\MicroErp\Theme\ShortCode\ThemeShortCodeInterface;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;

/**
 * Class ThemeConfiguration
 * @package AllInData\MicroErp\Theme
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
            new DisabledAdminBar(),
            new ExtendMainMenuByAdminMenu()
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
            new LoginForm()
        ];
    }
}