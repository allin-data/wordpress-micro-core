<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Module;

use AllInData\Dgr\Cms\Model\Factory\ElementorCmsCategory as CategoryFactory;
use AllInData\Dgr\Core\Module\PluginModuleInterface;
use Elementor\Core\Common\Modules\Finder\Categories_Manager;

/**
 * Class ElementorCategory
 * @package AllInData\Dgr\Cms\Module
 */
class ElementorCategory implements PluginModuleInterface
{
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * ElementorCategory constructor.
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(CategoryFactory $categoryFactory)
    {
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_action('elementor/finder/categories/init', [$this, 'addElementorCategory']);
    }

    /**
     * @param Categories_Manager $categoriesManager
     */
    public function addElementorCategory($categoriesManager)
    {
        $categoriesManager->add_category('allindata-dgr-cms-category', $this->categoryFactory->create());
    }
}