<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Widget\Elementor;

use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;
use AllInData\MicroErp\Mdm\Model\ElementorMdmCategory;

/**
 * Class Calendar
 * @package AllInData\MicroErp\Planning\Widget\Elementor
 */
class Calendar extends AbstractElementorWidget
{
    const WIDGET_NAME = 'allindata-micro-erp-planning-calendar';

    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return static::WIDGET_NAME;
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('Calendar', AID_MICRO_ERP_PLANNING_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-calendar';
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
    protected function render()
    {
        echo '<div class="'.$this->get_name().'-elementor-widget">';
        echo do_shortcode("[micro_erp_planning_calendar]");
        echo '</div>';
    }
}