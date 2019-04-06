<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\ShortCode;

/**
 * Class AbstractPlugin
 * @package AllInData\Dgr\Core
 */
abstract class AbstractShortCode implements PluginShortCodeInterface
{
    /**
     * @var string
     */
    private $templatePath;

    /**
     * AbstractPlugin constructor.
     * @param string $templatePath
     */
    public function __construct(string $templatePath = '')
    {
        $this->templatePath = $templatePath;
    }

    /**
     * @inheritdoc
     */
    abstract public function init();

    /**
     * @param string $templateName
     * @param array $args
     */
    protected function getTemplate($templateName, $args = [])
    {
        global $wp_query;
        if (is_array($args) && isset($args)) {
            $wp_query->query_vars = array_merge($wp_query->query_vars, $args);
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
        $template = $this->templatePath . $templateName . '.php';
        return \apply_filters('dgr_locate_template', $template, $templateName, $this->templatePath);
    }
}