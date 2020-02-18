<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Widget;

use Elementor\Widget_Base;
use Elementor\Plugin as ElementorPlugin;

/**
 * Class AbstractElementorWidget
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractElementorWidget extends Widget_Base implements ElementorWidgetInterface
{
    /**
     * @inheritDoc
     */
    public function initialize()
    {
        add_action('elementor/widgets/widgets_registered', [$this, 'registerWidget']);
    }

    /**
     * @inheritDoc
     */
    public function registerWidget()
    {
        ElementorPlugin::instance()->widgets_manager->register_widget_type($this);
    }

    /**
     * @param string $name
     * @param array $attributes
     * @return string
     */
    protected function getShortCode(string $name, array $attributes = []): string
    {
        $attributesString = '';
        foreach ($attributes as $key => $value) {
            if (!is_string($value) && !is_numeric($value)) {
                continue;
            }
            $attributesString .= sprintf(
                ' %s="%s"',
                $key,
                $value
            );
        }

        if (empty($attributes) || 0 === strlen(trim($attributesString))) {
            return sprintf(
                '[%s]',
                $name
            );
        }

        return sprintf(
            '[%s %s]',
            $name,
            $attributesString
        );
    }

    /**
     * @param string $name
     * @param array $attributeKeys
     * @return string
     */
    protected function getShortCodePreview(string $name, array $attributeKeys = []): string
    {
        $attributesString = '';
        foreach ($attributeKeys as $key) {
            $attributesString .= sprintf(
                ' %s="{{{ settings.%s }}}"',
                $key,
                $key
            );
        }

        if (empty($attributes) || 0 === strlen(trim($attributesString))) {
            return sprintf(
                '[%s]',
                $name
            );
        }

        return sprintf(
            '[%s %s]',
            $name,
            $attributesString
        );
    }
}
