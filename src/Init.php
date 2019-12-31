<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core;

add_action('plugins_loaded', function () {
    do_action('allindata/micro/core/init');
});

/**
 * Class Init
 * @package AllInData\Micro\Core
 */
class Init
{
    static $PLUGIN_CONFIGURATION = '';
    static $VERSION = '1.2';
    static $SLUG = 'allindata-micro-core-core';
    static $TEXTDOMAIN = 'allindata-micro-core-core';
    static $TEMPLATE_DIR = __DIR__ . '/view/';
    static $TEMP_DIR = ABSPATH . 'tmp/';
    static $FILE = __FILE__;
    static $PATH;
    static $URL;

    static public function init()
    {
        static::$PATH = \plugin_dir_path(__FILE__);
        static::$URL = \plugin_dir_url(__FILE__);

        if (!static::checkDependencies()) {
            return;
        }

        static::loadPlugin(
            static::$SLUG,
            static::$TEMP_DIR,
            static::$PLUGIN_CONFIGURATION
        );
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

        /** @var \AllInData\Micro\Core\PluginInterface $app */
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
