<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Theme;

use AllInData\Dgr\Theme\Block\Navigation\Menu;
use AllInData\Dgr\Theme\Module\ForceLogin;
use AllInData\Dgr\Theme\Module\ThemeModuleInterface;
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
        return new Theme($this->getThemeModules());
    }

    /**
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
            new ForceLogin()
        ];
    }
}