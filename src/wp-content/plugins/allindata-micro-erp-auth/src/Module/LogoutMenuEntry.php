<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Module;

use AllInData\MicroErp\Auth\Helper\LoginPageStateHelper;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use Countable;
use stdClass;
use WP_Post;
use WP_Query;

/**
 * Class LogoutMenuEntry
 * @package AllInData\MicroErp\Auth\Module
 */
class LogoutMenuEntry implements PluginModuleInterface
{
    /**
     * @var LoginPageStateHelper
     */
    private $loginPageHelper;
    /**
     * @var array
     */
    private $logoutMenuSet = [];

    /**
     * LogoutMenuEntry constructor.
     * @param LoginPageStateHelper $loginPageHelper
     * @param array $logoutMenuSet
     */
    public function __construct(LoginPageStateHelper $loginPageHelper, array $logoutMenuSet)
    {
        $this->loginPageHelper = $loginPageHelper;
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
        $items = $this->clearItems($items);
        if (is_admin() ||
            !is_user_logged_in() ||
            !$this->isApplicableMenu($menu->term_id) ||
            $this->hasLogoutMenutEntry($items)) {
            return $items;
        }

        $itemCount = 0;
        if (is_array($items) || ($items instanceof Countable)) {
            $itemCount = count($items);
        }

        $items[] = $this->getLogoutMenuEntry($menu->term_id, $itemCount + 99999);
        return $items;
    }

    /**
     * @param array $items
     * @return array
     */
    private function clearItems(array $items): array
    {
        foreach ($items as $idx => $item) {
            /** @var WP_Post $item */
            if ($item->post_name === 'logout' &&
                empty($item->url)) {
                unset($items[$idx]);
            }
        }
        return $items;
    }

    /**
     * @param array $items
     * @return bool
     */
    private function hasLogoutMenutEntry(array $items): bool
    {
        foreach ($items as $item) {
            /** @var WP_Post $item */
            if ($item->post_name === 'logout') {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $menuId
     * @param $position
     * @return WP_Post
     */
    private function getLogoutMenuEntry($menuId, $position): WP_Post
    {
        $logoutUrl = wp_logout_url(get_post_permalink($this->loginPageHelper->getLoginPagePost()));

        $query = new WP_Query([
            'post_status' => 'publish',
            'post_type' => 'nav_menu_item',
            'name' => 'logout'
        ]);
        $result = $query->get_posts();

        if (1 !== $query->post_count) {

            $post = new WP_Post(new stdClass());
            $post->post_title = __('Logout', AID_MICRO_ERP_AUTH_TEXTDOMAIN);
            $post->post_name = 'logout';
            $post->post_status = "publish";
            $post->post_type = "nav_menu_item";
            $post->post_parent = 0;
            $post->menu_order = $position;
            $post->guid = home_url('logout');

            $postId = wp_insert_post((array)$post);
            $post = get_post($postId);
        } else {
            $post = array_shift($result);
        }
        $post->title = __('Logout', AID_MICRO_ERP_AUTH_TEXTDOMAIN);
        $post->menu_item_parent = 0;
        $post->menu_order = $position;
        $post->guid = home_url('logout');
        $post->url = $logoutUrl;

        wp_update_post((array)$post);

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