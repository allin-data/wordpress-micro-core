<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Controller;

/**
 * Class AbstractAdminController
 * @package AllInData\MicroErp\Core\Controller
 */
abstract class AbstractAdminController extends AbstractController implements PluginControllerInterface
{
    /**
     * @return bool
     */
    protected function hasAllowanceCapabilities(): bool
    {
        return current_user_can('administrator');
    }
}