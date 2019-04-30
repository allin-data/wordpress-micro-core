<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Report\Module;

use AllInData\MicroErp\Core\Model\GenericFactory;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use Elementor\Elements_Manager;

/**
 * Class ElementorCategory
 * @package AllInData\MicroErp\Report\Module
 */
class ElementorCategory implements PluginModuleInterface
{
    /**
     * @var GenericFactory
     */
    private $categoryFactory;
    /**
     * @var Elements_Manager
     */
    private $elementsManager;

    /**
     * ElementorCategory constructor.
     * @param GenericFactory $categoryFactory
     * @param Elements_Manager $elementsManager
     */
    public function __construct(GenericFactory $categoryFactory, Elements_Manager $elementsManager)
    {
        $this->categoryFactory = $categoryFactory;
        $this->elementsManager = $elementsManager;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_action('elementor/elements/categories_registered', [$this, 'addElementorCategory']);
    }

    /**
     *
     */
    public function addElementorCategory()
    {
        $category = $this->categoryFactory->create();
        $this->elementsManager->add_category(
            $category->getName(),
            [
                'title' => $category->get_title(),
                'icon' => 'fa fa-plug',
            ]
        );
    }
}