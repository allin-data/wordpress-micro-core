<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\ShortCode;

use function apply_filters;
use function load_template;

/**
 * Class AbstractPlugin
 * @package AllInData\MicroErp\Core
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
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
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
        load_template($templateFile, false);
    }

    /**
     * @param array $attributes
     * @param array $defaultAttributes
     * @param string $tagName
     * @return array
     */
    protected function prepareAttributes(array $attributes, array $defaultAttributes, string $tagName)
    {
        $attributes = array_change_key_case((array)$attributes, CASE_LOWER);
        // override default attributes with user attributes
        return shortcode_atts($defaultAttributes, $attributes, $tagName);
    }

    /**
     * @param string $templateName
     * @return mixed|void
     */
    private function loadTemplate($templateName)
    {
        $template = $this->getTemplatePath() . $templateName . '.php';
        return apply_filters('micro_erp_locate_template', $template, $templateName, $this->getTemplatePath());
    }
}
