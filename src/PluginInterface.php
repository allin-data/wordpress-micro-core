<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core;

/**
 * Interface PluginInterface
 * @package AllInData\MicroErp\Core
 */
interface PluginInterface
{
    /**
     * Init plugin
     * Required by Wordpress
     */
    public function init();

    /**
     * Init plugin
     */
    public function doInit();

    /**
     * Load plugin
     */
    public function load();
}