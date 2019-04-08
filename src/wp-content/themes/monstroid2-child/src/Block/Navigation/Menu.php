<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\Block\Navigation;

/**
 * Class Menu
 * @package AllInData\MicroErp\Theme\Block\Navigation
 */
class Menu
{
    public function isShown() : bool
    {
        return is_user_logged_in();
    }
}