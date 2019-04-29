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
 * Plugin Name: AllInData Micro ERP Auth
 * Description: All.In Data - Micro ERP - Authentication and Authorization
 * Version: 1.0
 * Depends: AllInData Micro ERP Core, Elementor Page Builder
 * Author: All.In Data GmbH
 * Author URI: https://www.all-in-data.de
 * Text Domain: allindata-micro-erp-auth
 * Domain Path: /languages
 * License: proprietary
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 5.0.0
 * WC tested up to: 5.1.0
 */

define('AID_MICRO_ERP_AUTH_VERSION', '1.0');
define('AID_MICRO_ERP_AUTH_SLUG', 'allindata-micro-erp-auth');
define('AID_MICRO_ERP_AUTH_TEXTDOMAIN', 'allindata-micro-erp-auth');
define('AID_MICRO_ERP_AUTH_TEMPLATE_DIR', __DIR__ . '/view/');
define('AID_MICRO_ERP_AUTH_TEMP_DIR', ABSPATH . 'tmp/');
define('AID_MICRO_ERP_AUTH_PATH', plugin_dir_path(__FILE__));
define('AID_MICRO_ERP_AUTH_URL', plugin_dir_url(__FILE__));

defined('ABSPATH') or exit;

require __DIR__ . '/vendor/autoload.php';

class AllInDataMicroErpAuth
{
    static public function init()
    {
        if (!static::checkDependencies()) {
            return;
        }

        $config = new \bitExpert\Disco\BeanFactoryConfiguration(AID_MICRO_ERP_AUTH_TEMP_DIR);
        $config->setProxyAutoloader(
            new \ProxyManager\Autoloader\Autoloader(
                new \ProxyManager\FileLocator\FileLocator(AID_MICRO_ERP_AUTH_TEMP_DIR),
                new \ProxyManager\Inflector\ClassNameInflector(AID_MICRO_ERP_AUTH_SLUG)
            )
        );
        $beanFactory = new \bitExpert\Disco\AnnotationBeanFactory(
            \AllInData\MicroErp\Auth\PluginConfiguration::class,
            [],
            $config
        );
        \bitExpert\Disco\BeanFactoryRegistry::register($beanFactory);

        /** @var \AllInData\MicroErp\Core\PluginInterface $app */
        $app = $beanFactory->get('PluginApp');
        $app->doInit();
    }

    /**
     * @return bool
     */
    static private function checkDependencies(): bool
    {
        // --- AllInData Micro ERP Core
        if (!version_compare(AID_MICRO_ERP_CORE_VERSION, '1.0', '>=')) {
            return false;
        }
        // --- Elementor Page Builder
        if (!version_compare(ELEMENTOR_VERSION, '2.5', '>=')) {
            return false;
        }
        return true;
    }
}
add_action('elementor/init', [AllInDataMicroErpAuth::class, 'init']);