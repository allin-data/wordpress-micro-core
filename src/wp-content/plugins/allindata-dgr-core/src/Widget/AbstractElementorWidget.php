<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\Widget;

use Elementor\Widget_Base;
use Elementor\Plugin as ElementorPlugin;

/**
 * Class AbstractElementorWidget
 * @package AllInData\Dgr\Core\Model
 */
abstract class AbstractElementorWidget extends Widget_Base implements ElementorWidgetInterface
{
    /**
     * @inheritDoc
     */
    public function init()
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
}
