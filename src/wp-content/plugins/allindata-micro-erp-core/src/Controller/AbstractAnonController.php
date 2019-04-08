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
abstract class AbstractAnonController extends AbstractController implements PluginControllerInterface
{
    /**
     * @inheritDoc
     */
    protected function isAllowed(): bool
    {
        return true;
    }
}