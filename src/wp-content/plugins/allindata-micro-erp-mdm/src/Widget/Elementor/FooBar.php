<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Widget\Elementor;

use AllInData\MicroErp\Mdm\Model\ElementorMdmCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;

/**
 * Class FooBar
 * @package AllInData\MicroErp\Mdm\Widget\Elementor
 */
class FooBar extends AbstractElementorWidget
{
    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'foobar';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('Foobar', AID_MICRO_ERP_MDM_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-code';
    }

    /**
     * @inheritDoc
     */
    public function get_categories()
    {
        return [ElementorMdmCategory::CATEGORY_NAME];
    }

    /**
     * @inheritDoc
     */
    protected function _register_controls()
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'url',
            [
                'label' => __('URL to embed', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'url',
                'placeholder' => __('https://your-link.com', AID_MICRO_ERP_MDM_TEXTDOMAIN),
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @inheritDoc
     */
    protected function render()
    {

        $settings = $this->get_settings_for_display();

        $html = wp_oembed_get($settings['url']);

        echo '<div class="foobar-elementor-widget">';

        echo ($html) ? $html : $settings['url'];

        echo '</div>';
    }
}