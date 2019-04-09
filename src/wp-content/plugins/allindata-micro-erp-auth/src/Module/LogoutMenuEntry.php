<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Auth\Module;

use AllInData\MicroErp\Mdm\Model\Factory\ElementorMdmCategory as CategoryFactory;
use AllInData\MicroErp\Core\Module\PluginModuleInterface;
use Elementor\Elements_Manager;

/**
 * Class LogoutMenuEntry
 * @package AllInData\MicroErp\Auth\Module
 */
class LogoutMenuEntry implements PluginModuleInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        add_filter('wp_nav_menu_items', [$this, 'addLogoutMenuEntry'], 10, 2);
    }

    /**
     * add link to main menu, trigger logout action
     */
    public function addLogoutMenuEntry($items, $args)
    {
        $menuType = $args->theme_location ?: null;
        if (!is_user_logged_in() || $menuType !== 'main') {
            return $items;
        }
        $logoutUrl = wp_logout_url(get_permalink());
        $logoutItemEntry = '<li class="logout"><a href="' . $logoutUrl . '">' . __('Logout', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) . '</a></li>';
        $items = $items . $logoutItemEntry;
        return $items;
    }
}