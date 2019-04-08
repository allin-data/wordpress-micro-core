<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

define('AID_MICRO_ERP_THEME_VERSION', '1.0');
define('AID_MICRO_ERP_THEME_SLUG', 'allindata-micro-erp-theme');
define('AID_MICRO_ERP_THEME_TEXTDOMAIN', 'allindata-micro-erp-theme');
define('AID_MICRO_ERP_THEME_TEMPLATE_DIR', __DIR__ . '/template-parts/');
define('AID_MICRO_ERP_THEME_TEMP_DIR', ABSPATH . 'tmp/');

defined('ABSPATH') or exit;

require __DIR__ . '/vendor/autoload.php';

class AllInDataMicroErpTheme
{
    static function init()
    {
        $config = new \bitExpert\Disco\BeanFactoryConfiguration(AID_MICRO_ERP_THEME_TEMP_DIR);
        $config->setProxyAutoloader(
            new \ProxyManager\Autoloader\Autoloader(
                new \ProxyManager\FileLocator\FileLocator(AID_MICRO_ERP_THEME_TEMP_DIR),
                new \ProxyManager\Inflector\ClassNameInflector(AID_MICRO_ERP_THEME_SLUG)
            )
        );
        $beanFactory = new \bitExpert\Disco\AnnotationBeanFactory(
            \AllInData\MicroErp\Theme\ThemeConfiguration::class,
            [],
            $config
        );
        \bitExpert\Disco\BeanFactoryRegistry::register($beanFactory);

        /** @var \AllInData\MicroErp\Theme\Theme $theme */
        $theme = $beanFactory->get('Theme');
        $theme->init();
    }
}
add_action('elementor/init', [AllInDataMicroErpTheme::class, 'init']);