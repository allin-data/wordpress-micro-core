<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core;

/**
 * Class AbstractPlugin
 * @package AllInData\Dgr\Core
 */
abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        //
    }

    /**
     * @inheritdoc
     */
    abstract public function load();
}
