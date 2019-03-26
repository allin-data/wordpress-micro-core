<?php

declare(strict_types=1);

/*
Copyright (C) 2018 All.In Data GmbH

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Plugin Name: AllInData Dgr Core
 * Description: All.In Data - Dgr Core
 * Version: 1.0
 * Author: All.In Data GmbH
 * Author URI: https://www.all-in-data.de
 * Text Domain: allindata-dgr-core
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 5.0.0
 * WC tested up to: 5.1.0
 */

define('AID_DGR_CORE_VERSION', '1.0');
define('AID_DGR_CORE_SLUG', 'allindata-dgr-core');
define('AID_DGR_CORE_TEXTDOMAIN', 'allindata-dgr-core');
define('AID_DGR_CORE_TEMPLATE_DIR', __DIR__ . '/view/');
define('AID_DGR_CORE__TEMP_DIR', ABSPATH . 'tmp/');

defined('ABSPATH') or exit;

require __DIR__ . '/vendor/autoload.php';
