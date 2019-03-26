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
 * Plugin Name: AllInData Dgr CMS
 * Description: All.In Data - Dgr CMS
 * Version: 1.0
 * Depends: AllInData Dgr Core
 * Author: All.In Data GmbH
 * Author URI: https://www.all-in-data.de
 * Text Domain: allindata-dgr-cms
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 5.0.0
 * WC tested up to: 5.1.0
 */

define('AID_DGR_CMS_VERSION', '1.0');
define('AID_DGR_CMS_SLUG', 'allindata-dgr-cms');
define('AID_DGR_CMS_TEXTDOMAIN', 'allindata-dgr-cms');
define('AID_DGR_CMS_TEMPLATE_DIR', __DIR__ . '/view/');
define('AID_DGR_CMS__TEMP_DIR', ABSPATH . 'tmp/');

defined('ABSPATH') or exit;

require __DIR__ . '/vendor/autoload.php';

$config = new \bitExpert\Disco\BeanFactoryConfiguration(AID_DGR_CMS__TEMP_DIR);
$config->setProxyAutoloader(
    new \ProxyManager\Autoloader\Autoloader(
        new \ProxyManager\FileLocator\FileLocator(AID_DGR_CMS__TEMP_DIR),
        new \ProxyManager\Inflector\ClassNameInflector(AID_DGR_CMS_SLUG)
    )
);
$beanFactory = new \bitExpert\Disco\AnnotationBeanFactory(
    \AllInData\Dgr\Cms\PluginConfiguration::class,
    [],
    $config
);
\bitExpert\Disco\BeanFactoryRegistry::register($beanFactory);

/** @var \AllInData\Dgr\Core\PluginInterface $app */
$app = $beanFactory->get('PluginApp');
$app->load();
