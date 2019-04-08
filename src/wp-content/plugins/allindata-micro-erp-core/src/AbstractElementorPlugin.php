<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core;

use AllInData\MicroErp\Core\Controller\PluginControllerInterface;
use AllInData\MicroErp\Core\ShortCode\PluginShortCodeInterface;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use AllInData\MicroErp\Core\Widget\ElementorWidgetInterface;

/**
 * Class AbstractPlugin
 * @package AllInData\MicroErp\Core
 */
abstract class AbstractElementorPlugin extends AbstractPlugin
{
    /**
     * @var ElementorWidgetInterface[]
     */
    private $widgets = [];

    /**
     * AbstractPlugin constructor.
     * @param string $templatePath
     * @param PluginModuleInterface[] $modules
     * @param PluginControllerInterface[] $controllers
     * @param PluginShortCodeInterface[] $shortCodes
     * @param ElementorWidgetInterface[] $widgets
     */
    public function __construct(
        string $templatePath,
        array $modules = [],
        array $controllers = [],
        array $shortCodes = [],
        array $widgets = []
    ) {
        parent::__construct($templatePath, $modules, $controllers, $shortCodes);
        $this->widgets = $widgets;
    }

    /**
     * @inheritdoc
     */
    public function doInit()
    {
        foreach ($this->widgets as $widget) {
            $widget->init();
        }

        parent::doInit();
    }
}
