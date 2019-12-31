<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\ShortCode;

use AllInData\Micro\Core\Block\AbstractBlock;
use function apply_filters;
use function load_template;

/**
 * Class AbstractPlugin
 * @package AllInData\Micro\Core
 */
abstract class AbstractShortCode implements PluginShortCodeInterface
{
    const SHORTCODE_NAME = '';
    const TEMPLATE_NAME = '';

    /**
     * @var string
     */
    protected $templatePath;
    /**
     * @var AbstractBlock
     */
    protected $block;

    /**
     * AbstractShortCode constructor.
     * @param string $templatePath
     * @param AbstractBlock $block
     */
    public function __construct(string $templatePath, AbstractBlock $block)
    {
        $this->templatePath = $templatePath;
        $this->block = $block;
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
        add_shortcode($this->getShortCodeName(), [$this, 'addShortCode']);
    }

    /**
     * @inheritdoc
     */
    public function addShortCode($attributes, $content, $name)
    {
        if(!$this->beforeExecute()) {
            return '';
        }

        if (empty($attributes)) {
            $attributes = [];
        }
        $attributes = $this->prepareAttributes($attributes, $this->getDefaultAttributeMap(), $name);

        $block = clone $this->block;
        $block->setAttributes($attributes);

        ob_start();
        $this->getTemplate($this->getTemplateName(), [
            'block' => $block
        ]);
        $output = ob_get_clean();

        return $output;
    }

    /**
     * @return bool
     */
    protected function beforeExecute(): bool
    {
        if (is_admin()) {
            return false;
        }
        return true;
    }

    /**
     * @return string
     */
    protected function getShortCodeName(): string
    {
        return static::SHORTCODE_NAME;
    }

    /**
     * @return string
     */
    protected function getTemplateName(): string
    {
        return static::TEMPLATE_NAME;
    }

    /**
     * @return array
     */
    protected function getDefaultAttributeMap(): array
    {
        return [];
    }

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
        return apply_filters('micro_core_locate_template', $template, $templateName, $this->getTemplatePath());
    }
}
