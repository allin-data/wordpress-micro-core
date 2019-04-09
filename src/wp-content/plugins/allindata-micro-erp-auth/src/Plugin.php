<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth;

use AllInData\MicroErp\Core\AbstractElementorPlugin;
use AllInData\MicroErp\Core\PluginInterface;

/**
 * Class Plugin
 * @package AllInData\MicroErp\Auth
 */
class Plugin extends AbstractElementorPlugin implements PluginInterface
{
    /**
     * @inheritdoc
     */
    public function load()
    {
        // Administration menu
        add_action('plugins_loaded', array(self::class, 'init'), 999);
    }
}
