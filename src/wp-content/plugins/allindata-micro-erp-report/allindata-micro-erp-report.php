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
 * Plugin Name: AllInData Micro ERP Report
 * Description: All.In Data - Micro ERP - Report
 * Version: 1.0
 * Depends: AllInData Micro ERP Core, Elementor Page Builder
 * Author: All.In Data GmbH
 * Author URI: https://www.all-in-data.de
 * Text Domain: allindata-micro-erp-report
 * Domain Path: /languages
 * License: proprietary
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * WC requires at least: 5.0.0
 * WC tested up to: 5.1.0
 */

define('AID_MICRO_ERP_REPORT_VERSION', '1.0');
define('AID_MICRO_ERP_REPORT_SLUG', 'allindata-micro-erp-report');
define('AID_MICRO_ERP_REPORT_TEXTDOMAIN', 'allindata-micro-erp-report');
define('AID_MICRO_ERP_REPORT_TEMPLATE_DIR', __DIR__ . '/view/');
define('AID_MICRO_ERP_REPORT_TEMP_DIR', ABSPATH . 'tmp/');
define('AID_MICRO_ERP_REPORT_PATH', plugin_dir_path(__FILE__));
define('AID_MICRO_ERP_REPORT_URL', plugin_dir_url(__FILE__));
define('AID_MICRO_ERP_REPORT_FILE', __FILE__);

defined('ABSPATH') or exit;

require __DIR__ . '/vendor/autoload.php';

class AllInDataMicroErpReport
{
    /**
     * Init
     */
    static public function init()
    {
        if (!static::checkDependencies()) {
            return;
        }

        $config = new \bitExpert\Disco\BeanFactoryConfiguration(AID_MICRO_ERP_REPORT_TEMP_DIR);
        $config->setProxyAutoloader(
            new \ProxyManager\Autoloader\Autoloader(
                new \ProxyManager\FileLocator\FileLocator(AID_MICRO_ERP_REPORT_TEMP_DIR),
                new \ProxyManager\Inflector\ClassNameInflector(AID_MICRO_ERP_REPORT_SLUG)
            )
        );
        $beanFactory = new \bitExpert\Disco\AnnotationBeanFactory(
            \AllInData\MicroErp\Report\PluginConfiguration::class,
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
        // --- AllInData Micro ERP Auth
        if (!version_compare(AID_MICRO_ERP_AUTH_VERSION, '1.0', '>=')) {
            return false;
        }
        // --- AllInData Micro ERP MDM
        if (!version_compare(AID_MICRO_ERP_MDM_VERSION, '1.0', '>=')) {
            return false;
        }
        // --- AllInData Micro ERP Planning
        if (!version_compare(AID_MICRO_ERP_PLANNING_VERSION, '1.0', '>=')) {
            return false;
        }
        // --- AllInData Micro ERP Resource
        if (!version_compare(AID_MICRO_ERP_RESOURCE_VERSION, '1.0', '>=')) {
            return false;
        }
        return true;
    }

    /**
     * Install Plugin
     */
    static function installPlugin()
    {
        self::init();
        $beanFactory = \bitExpert\Disco\BeanFactoryRegistry::getInstance();
        /** @var \AllInData\MicroErp\Report\Plugin $app */
        $app = $beanFactory->get('PluginApp');
        $app->installPlugin();
    }
}
register_activation_hook(AID_MICRO_ERP_REPORT_FILE, [AllInDataMicroErpReport::class, 'installPlugin']);
add_action('elementor/init', [AllInDataMicroErpReport::class, 'init']);