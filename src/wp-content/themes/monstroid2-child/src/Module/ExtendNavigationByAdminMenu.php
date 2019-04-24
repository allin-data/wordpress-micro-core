<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\Module;

use Countable;
use WP_Post;

/**
 * Class ExtendNavigationByAdminMenu
 * @package AllInData\MicroErp\Theme\Module
 */
class ExtendNavigationByAdminMenu implements ThemeModuleInterface
{
    /**
     * @var string
     */
    private $adminMenuSlug;
    /**
     * @var array
     */
    private $applicableMenuSet;

    /**
     * ExtendNavigationByAdminMenu constructor.
     * @param string $adminMenuSlug
     * @param array $applicableMenuSet
     */
    public function __construct($adminMenuSlug, array $applicableMenuSet)
    {
        $this->adminMenuSlug = $adminMenuSlug;
        $this->applicableMenuSet = $applicableMenuSet;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_filter('wp_get_nav_menu_items', [$this, 'extendNavigation'], 900, 3);
    }

    /**
     * @param array $items An array of menu item post objects.
     * @param object $menu The menu object.
     * @param array $args An array of arguments used to retrieve menu item objects.
     * @return array
     * @see wp_get_nav_menu_items()
     */
    public function extendNavigation($items, $menu, $args)
    {
        if (is_admin() ||
            !current_user_can('administrator') ||
            !$this->isApplicableMenu($menu->term_id)) {
            return $items;
        }

        $adminMenu = wp_get_nav_menu_object($this->adminMenuSlug);
        $adminMenuItems = wp_get_nav_menu_items($adminMenu->term_id, ['post_status' => 'publish']);

        if (!$adminMenuItems) {
            return $items;
        }

        $menuCount = 9999;
        if (is_array($items) || ($items instanceof Countable)) {
            $menuCount += count($items);
        }
        foreach ($adminMenuItems as $adminMenuItem) {
            /** @var WP_Post menu_order */
            $adminMenuItem->menu_order = ++$menuCount;
        }
        $items = array_merge($items, $adminMenuItems);
        return $items;
    }

    /**
     * @param int|string $menuId
     * @return bool
     */
    private function isApplicableMenu($menuId): bool
    {
        $locations = get_nav_menu_locations();
        foreach ($this->applicableMenuSet as $menuSelector) {
            $mainLocationId = $locations[$menuSelector] ?: null;
            if (!$mainLocationId) {
                continue;
            }

            if ($menuId == $mainLocationId) {
                return true;
            }
        }

        return false;
    }
}