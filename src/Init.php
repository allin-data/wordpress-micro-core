<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core;

define('AID_MICRO_ERP_CORE_VERSION', '1.0');
define('AID_MICRO_ERP_CORE_SLUG', 'allindata-micro-erp-core');
define('AID_MICRO_ERP_CORE_TEXTDOMAIN', 'allindata-micro-erp-core');
define('AID_MICRO_ERP_CORE_TEMPLATE_DIR', __DIR__ . '/view/');
define('AID_MICRO_ERP_CORE_TEMP_DIR', ABSPATH . 'tmp/');
define('AID_MICRO_ERP_CORE_FILE', __FILE__);

add_action('plugins_loaded', function () {
    do_action('allindata/micro/erp/init');
});

/**
 * Class Init
 * @package AllInData\MicroErp\Core
 */
class Init
{
    static public function init()
    {
        if (!static::checkDependencies()) {
            return;
        }

        //static::loadPlugin(
        //    AID_MICRO_ERP_CORE_SLUG,
        //    AID_MICRO_ERP_CORE_TEMP_DIR,
        //    PluginConfiguration::class
        //);
    }

    /**
     * @return void
     */
    static protected function loadPlugin(string $pluginSlug, string $cacheFolder, string $configurationClassName): void
    {

        // create cache folder if not exist
        if (!file_exists($cacheFolder)) {
            mkdir($cacheFolder, 0660, true);
        }

        $config = new \bitExpert\Disco\BeanFactoryConfiguration($cacheFolder);
        $config->setProxyAutoloader(
            new \ProxyManager\Autoloader\Autoloader(
                new \ProxyManager\FileLocator\FileLocator($cacheFolder),
                new \ProxyManager\Inflector\ClassNameInflector($pluginSlug)
            )
        );

        $beanFactory = new \bitExpert\Disco\AnnotationBeanFactory(
            $configurationClassName,
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
    static protected function checkDependencies(): bool
    {
        return true;
    }
}
