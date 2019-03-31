<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\Controller;

/**
 * Class AbstractAdminController
 * @package AllInData\Dgr\Core\Controller
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