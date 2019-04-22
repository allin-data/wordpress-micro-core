<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Module;

use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use Countable;
use stdClass;
use WP_Post;

/**
 * Class LogoutMenuEntry
 * @package AllInData\MicroErp\Auth\Module
 */
class LogoutMenuEntry implements PluginModuleInterface
{
    /**
     * @var array
     */
    private $logoutMenuSet = [];

    /**
     * LogoutMenuEntry constructor.
     * @param array $logoutMenuSet
     */
    public function __construct(array $logoutMenuSet)
    {
        $this->logoutMenuSet = $logoutMenuSet;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_filter('wp_get_nav_menu_items', [$this, 'addLogoutMenuEntry'], 999, 3);
    }

    /**
     * add link to main menu, trigger logout action
     */
    public function addLogoutMenuEntry($items, $menu, $args)
    {
        if (!is_user_logged_in() || !$this->isApplicableMenu($menu->term_id)) {
            return $items;
        }

        $itemCount = 0;
        if (is_array($items) || ($items instanceof Countable)) {
            $itemCount = count($items);
        }

        $items[] = $this->getLogoutMenuEntry($menu->term_id, $itemCount + 1000);
        return $items;
    }

    /**
     * @param $menuId
     * @param $position
     * @return WP_Post
     */
    private function getLogoutMenuEntry($menuId, $position): WP_Post
    {
        $post = new WP_Post(new stdClass());

        $post->post_title = __('Logout', AID_MICRO_ERP_AUTH_TEXTDOMAIN);
        $post->title = __('Logout', AID_MICRO_ERP_AUTH_TEXTDOMAIN);
        $post->post_name = __('Logout', AID_MICRO_ERP_AUTH_TEXTDOMAIN);
        $post->post_status = "publish";
        $post->post_type = "nav_menu_item";
        $post->menu_item_parent = $menuId;
        $post->menu_order = $position;
        $post->object = "custom";
        $post->type = "custom";
        $post->type_label = "Individueller Link";
        $post->guid = wp_logout_url(get_permalink());
        $post->url = wp_logout_url(get_permalink());

        return $post;
    }

    /**
     * @param int|string $menuId
     * @return bool
     */
    private function isApplicableMenu($menuId): bool
    {
        $locations = get_nav_menu_locations();
        foreach ($this->logoutMenuSet as $logoutMenuSelector) {
            $mainLocationId = $locations[$logoutMenuSelector] ?: null;
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