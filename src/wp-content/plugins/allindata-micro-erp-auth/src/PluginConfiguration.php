<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth;

use AllInData\MicroErp\Auth\Controller\Login;
use AllInData\MicroErp\Auth\Controller\Logout;
use AllInData\MicroErp\Auth\Helper\LoginPageStateHelper;
use AllInData\MicroErp\Auth\Module\LogoutMenuEntry;
use AllInData\MicroErp\Auth\ShortCode\LoginForm;
use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;
use AllInData\MicroErp\Auth\Module\ForceLogin;
use AllInData\MicroErp\Auth\Module\RegisterLoginPagePostState;
use bitExpert\Disco\Annotations\Configuration;
use bitExpert\Disco\Annotations\Bean;
use Exception;

/**
 * Class PluginConfiguration
 * @package AllInData\MicroErp\Auth
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
            AID_MICRO_ERP_AUTH_TEMPLATE_DIR,
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
            new \AllInData\MicroErp\Auth\Widget\Elementor\LoginForm()
        ];
    }

    /**
     * @return PluginModuleInterface[]
     */
    private function getPluginModules(): array
    {
        return [
            new ForceLogin(
                $this->getLoginPageStateHelper()
            ),
            new LogoutMenuEntry(),
            new RegisterLoginPagePostState(
                $this->getLoginPageStateHelper()
            )
        ];
    }

    /**
     * @return PluginControllerInterface[]
     */
    private function getPluginControllers(): array
    {
        return [
            new Login(),
            new Logout()
        ];
    }

    /**
     * @return PluginShortCodeInterface[]
     */
    private function getPluginShortCodes(): array
    {
        return [
            new LoginForm(
                AID_MICRO_ERP_AUTH_TEMPLATE_DIR,
                new \AllInData\MicroErp\Auth\Block\Login()
            )
        ];
    }

    /**
     * @return LoginPageStateHelper
     */
    private function getLoginPageStateHelper(): LoginPageStateHelper
    {
        return new LoginPageStateHelper();
    }
}