<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Module;

use AllInData\MicroErp\Mdm\Model\Factory\ElementorMdmAdminCategory as CategoryFactory;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use Elementor\Elements_Manager;

/**
 * Class ElementorAdminCategory
 * @package AllInData\MicroErp\Mdm\Module
 */
class ElementorAdminCategory implements PluginModuleInterface
{
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var Elements_Manager
     */
    private $elementsManager;

    /**
     * ElementorCategory constructor.
     * @param CategoryFactory $categoryFactory
     * @param Elements_Manager $elementsManager
     */
    public function __construct(CategoryFactory $categoryFactory, Elements_Manager $elementsManager)
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