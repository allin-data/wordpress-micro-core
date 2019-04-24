<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Widget\Elementor;

use AllInData\MicroErp\Core\Model\GenericCollection;
use AllInData\MicroErp\Resource\Model\ElementorResourceCategory as ElementorCategory;
use AllInData\MicroErp\Core\Widget\AbstractElementorWidget;
use AllInData\MicroErp\Resource\Model\ResourceType;

/**
 * Class ListOfResources
 * @package AllInData\MicroErp\Resource\Widget\Elementor
 */
class ListOfResources extends AbstractElementorWidget
{
    /**
     * @var GenericCollection
     */
    private static $resourceTypeCollection;

    /**
     * @param GenericCollection $resourceTypeCollection
     * @return ListOfResources
     */
    public function setResourceTypeCollection(GenericCollection $resourceTypeCollection): ListOfResources
    {
        static::$resourceTypeCollection = $resourceTypeCollection;
        return $this;
    }


    /**
     * @inheritDoc
     */
    public function get_name()
    {
        return 'allindata-micro-erp-resource-list-of-resources';
    }

    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('List of Resources', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function get_icon()
    {
        return 'fa fa-table';
    }

    /**
     * @inheritDoc
     */
    public function get_categories()
    {
        return [ElementorCategory::CATEGORY_NAME];
    }

    /**
     * @inheritDoc
     */
    protected function _register_controls()
    {
        /*
         * Section Main Configuration
         */
        $this->start_controls_section(
            'section_main_configuration',
            [
                'label' => __('Main Configuration', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('Enter the title', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
            ]
        );

        $typeSet = static::$resourceTypeCollection->load(GenericCollection::NO_LIMIT);
        sort($typeSet);
        $resourceTypeOptionSet = [];
        foreach ($typeSet as $type) {
            /** @var ResourceType $type */
            $resourceTypeOptionSet[$type->getId()] = $type->getLabel();
        }
        $this->add_control(
            'resource_type_id',
            [
                'label' => __('Resource Type', AID_MICRO_ERP_RESOURCE_TEXTDOMAIN),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $resourceTypeOptionSet
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

        echo '<div class="'.$this->get_name().'-elementor-widget">';
        echo do_shortcode($this->getShortCode('micro_erp_resource_grid_resource', $settings));
        echo '</div>';
    }
}