<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\Module;

/**
 * Interface PluginModuleInterface
 * @package AllInData\Dgr\Core\Module
 */
interface PluginModuleInterface
{
    /**
     * Init plugin module
     */
    public function init();
}