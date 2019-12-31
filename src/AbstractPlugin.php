<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core;

use AllInData\Micro\Core\Controller\PluginControllerInterface;
use AllInData\Micro\Core\ShortCode\PluginShortCodeInterface;
use AllInData\Micro\Core\Module\PluginModuleInterface;

/**
 * Class AbstractPlugin
 * @package AllInData\Micro\Core
 */
abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @var string
     */
    private $templatePath;
    /**
     * @var PluginModuleInterface[]
     */
    private $modules = [];
    /**
     * @var PluginControllerInterface[]
     */
    private $controllers = [];
    /**
     * @var PluginShortCodeInterface[]
     */
    private $shortCodes = [];

    /**
     * AbstractPlugin constructor.
     * @param string $templatePath
     * @param PluginModuleInterface[] $modules
     * @param PluginControllerInterface[] $controllers
     * @param PluginShortCodeInterface[] $shortCodes
     */
    public function __construct(
        string $templatePath,
        array $modules = [],
        array $controllers = [],
        array $shortCodes = []
    ) {
        $this->templatePath = $templatePath;
        $this->modules = $modules;
        $this->controllers = $controllers;
        $this->shortCodes = $shortCodes;
    }

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
    }

    /**
     * @inheritdoc
     */
    public function doInit()
    {
        foreach ($this->modules as $module) {
            $module->init();
        }

        foreach ($this->controllers as $controller) {
            $controller->init();
        }

        foreach ($this->shortCodes as $shortCode) {
            $shortCode->init();
        }

        $this->load();
    }

    /**
     * @inheritdoc
     */
    abstract public function load();

    /**
     * @param string $templateName
     * @param array $args
     */
    protected function getTemplate($templateName, $args = [])
    {
        if (is_array($args) && isset($args)) {
            extract($args);
        }

        $templateFile = $this->loadTemplate($templateName);
        if (!file_exists($templateFile)) {
            throw new \RuntimeException(sprintf(__('Template "%s" could not be found.'), $templateName));
        }
        \load_template($templateFile, false);
    }

    /**
     * @param string $templateName
     * @return mixed|void
     */
    private function loadTemplate($templateName)
    {
        $template = $this->getTemplatePath() . $templateName . '.php';
        return \apply_filters('micro_core_locate_template', $template, $templateName, $this->templatePath);
    }
}
