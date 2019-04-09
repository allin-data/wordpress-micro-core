<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Controller;

use Exception;

/**
 * Interface PluginControllerInterface
 * @package AllInData\MicroErp\Core\Controller
 */
interface PluginControllerInterface
{
    /**
     * Init plugin controller
     */
    public function init();

    /**
     * Executes controller action
     * @return mixed
     * @throws Exception
     */
    public function execute();
}