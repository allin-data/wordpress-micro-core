<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Helper;

/**
 * Class FrontendEmulator
 * @package AllInData\Micro\Core\Helper
 */
class FrontendEmulator
{
    /**
     * @var  \WP_Screen
     */
    static private $resumableScreen;

    /**
     * Enable emulated frontend screen scope
     */
    static public function enable()
    {
        if (!is_admin()) {
            return;
        }

        self::$resumableScreen = $GLOBALS['current_screen'] ?? null;
        $GLOBALS['current_screen'] = \WP_Screen::get('front');
    }

    /**
     * Disable emulated frontend screen scope
     */
    static public function disable()
    {
        if (is_admin()) {
            return;
        }

        $GLOBALS['current_screen'] = self::$resumableScreen;
    }
}