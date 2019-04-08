<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Module;

/**
 * Interface PluginModuleInterface
 * @package AllInData\MicroErp\Core\Module
 */
interface PluginModuleInterface
{
    /**
     * Init plugin module
     */
    public function init();
}