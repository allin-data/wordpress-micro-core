<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Role;

use Countable;
use WP_Post;
use WP_Role;

/**
 * Class AbstractRole
 * @package AllInData\MicroErp\Mdm\Model\Role
 */
abstract class AbstractRole implements RoleInterface
{
    const ROLE_LEVEL = '';

    /**
     * @var array
     */
    private $applicableMenuSet;

    /**
     * AbstractRole constructor.
     * @param array $applicableMenuSet
     */
    public function __construct(array $applicableMenuSet = [])
    {
        $this->applicableMenuSet = $applicableMenuSet;
        $this->init();
    }

    /**
     * @return void
     */
    public function installRole()
    {
        add_role($this->getRoleLevel(), $this->getRoleLabel(), $this->getCapabilities());
        do_action('role_install_' . $this->getRoleLevel(), $this->getRoleLevel());
    }

    /**
     * @return void
     */
    public function removeRole()
    {
        remove_role($this->getRoleLevel());
        do_action('role_remove_' . $this->getRoleLevel(), $this->getRoleLevel());
    }

    /**
     * @return string
     */
    abstract public function getRoleLabel(): string;

    /**
     * @return string
     */
    public function getRoleLevel(): string
    {
        return static::ROLE_LEVEL;
    }

    /**
     * @return WP_Role|null
     */
    public function getRoleInstance(): ?WP_Role
    {
        return get_role($this->getRoleLevel());
    }

    /**
     * @return array
     */
    public function getCapabilities(): array
    {
        $role = get_role($this->getRoleLevel());
        if (!$role) {
            return [];
        }
        return $role->capabilities;
    }

    /**
     * @return void
     */
    public function addRoleNavigationMenu()
    {
        register_nav_menu(
            'menu_' . $this->getRoleLevel(), sprintf(
                __('Menu %1$s', AID_MICRO_ERP_MDM_TEXTDOMAIN),
                $this->getRoleLabel()
            )
        );
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

        $menuSlug = 'menu_' . $this->getRoleLevel();

        $roleMenu = wp_get_nav_menu_object($menuSlug);
        $roleMenuItems = wp_get_nav_menu_items($roleMenu->term_id, ['post_status' => 'publish']);

        if (!$roleMenuItems) {
            return $items;
        }

        $menuCount = 0;
        if (is_array($items) || ($items instanceof Countable)) {
            $menuCount = count($items);
        }
        foreach ($roleMenuItems as $roleMenuItem) {
            /** @var WP_Post menu_order */
            $roleMenuItem->menu_order = $roleMenuItem->menu_order + $menuCount + 9999;
        }
        $items = array_merge($items, $roleMenuItems);
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

    /**
     * Init additional functionalities
     */
    protected function init()
    {
        add_action('init', [$this, 'addRoleNavigationMenu'], 10);
        add_filter('wp_get_nav_menu_items', [$this, 'extendNavigation'], 10, 3);
    }
}