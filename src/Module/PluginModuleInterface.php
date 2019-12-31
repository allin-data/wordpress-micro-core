<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Module;

/**
 * Interface PluginModuleInterface
 * @package AllInData\Micro\Core\Module
 */
interface PluginModuleInterface
{
    /**
     * Init plugin module
     */
    public function init();
}