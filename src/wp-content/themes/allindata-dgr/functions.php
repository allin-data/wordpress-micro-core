<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

define('AID_DGR_THEME_VERSION', '1.0');
define('AID_DGR_THEME_SLUG', 'allindata-dgr-theme');
define('AID_DGR_THEME_TEXTDOMAIN', 'allindata-dgr-theme');
define('AID_DGR_THEME_TEMPLATE_DIR', __DIR__ . '/view/');
define('AID_DGR_THEME_TEMP_DIR', ABSPATH . 'tmp/');

defined('ABSPATH') or exit;

require __DIR__ . '/vendor/autoload.php';

$config = new \bitExpert\Disco\BeanFactoryConfiguration(AID_DGR_THEME_TEMP_DIR);
$config->setProxyAutoloader(
    new \ProxyManager\Autoloader\Autoloader(
        new \ProxyManager\FileLocator\FileLocator(AID_DGR_THEME_TEMP_DIR),
        new \ProxyManager\Inflector\ClassNameInflector(AID_DGR_THEME_SLUG)
    )
);
$beanFactory = new \bitExpert\Disco\AnnotationBeanFactory(
    \AllInData\Dgr\Theme\ThemeConfiguration::class,
    [],
    $config
);
\bitExpert\Disco\BeanFactoryRegistry::register($beanFactory);

/** @var \AllInData\Dgr\Theme\Theme $theme */
$theme = $beanFactory->get('Theme');
$theme->init();