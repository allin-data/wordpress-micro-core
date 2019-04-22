<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme;

use AllInData\MicroErp\Theme\Module\DisabledAdminBar;
use AllInData\MicroErp\Theme\Module\ExtendNavigationByAdminMenu;
use AllInData\MicroErp\Theme\Module\ThemeModuleInterface;
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
            $this->getThemeModules()
        );
    }

    /**
     * @return ThemeModuleInterface[]
     */
    private function getThemeModules() : array
    {
        return [
            new DisabledAdminBar(),
            new ExtendNavigationByAdminMenu('adminmenu', [
                'main'
            ])
        ];
    }
}