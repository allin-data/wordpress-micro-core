<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\Module;

use AllInData\MicroErp\Mdm\Model\UserRole;
use WP_Post;

/**
 * Class ExtendMainMenuByAdminMenu
 * @package AllInData\MicroErp\Theme\Module
 */
class ExtendMainMenuByAdminMenu implements ThemeModuleInterface
{
    const ADMIN_MENU_SLUG = 'adminmenu';
    const MAIN_MENU_SLUG = 'hauptmenue';

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_filter('wp_get_nav_menu_items', [$this, 'extendMainMenu'], 10, 3);
    }

    /**
     * @param array $items An array of menu item post objects.
     * @param object $menu The menu object.
     * @param array $args An array of arguments used to retrieve menu item objects.
     * @return array
     * @see wp_get_nav_menu_items()
     */
    public function extendMainMenu($items, $menu, $args)
    {
        if (self::MAIN_MENU_SLUG !== $menu->slug ||
            self::ADMIN_MENU_SLUG === $menu->slug ||
            is_admin() ||
            !current_user_can(UserRole::ROLE_LEVEL_ADMINISTRATION)) {
            return $items;
        }
        $adminMenu = wp_get_nav_menu_object(self::ADMIN_MENU_SLUG);
        $adminMenuItems = wp_get_nav_menu_items($adminMenu->term_id, ['post_status' => 'publish']);

        $menuCount = count($items);
        foreach ($adminMenuItems as $adminMenuItem) {
            /** @var WP_Post menu_order */
            $adminMenuItem->menu_order = $adminMenuItem->menu_order + $menuCount;
        }
        $items = array_merge($items, $adminMenuItems);
        return $items;
    }
}