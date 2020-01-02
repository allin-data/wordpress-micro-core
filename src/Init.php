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
    const SLUG = 'allindata-micro-core-core';
    const VERSION = '1.3';
    const TEMPLATE_DIR = __DIR__ . '/../view/';
    const TEMP_DIR = ABSPATH . 'tmp/';
    const FILE = __FILE__;
    const PLUGIN_CONFIGURATION = '';

    protected static $instances = [];

    private $pluginConfiguration = '';
    private $version = '1.2';
    private $slug = 'allindata-micro-core';
    private $textdomain = 'allindata-micro-core';
    private $templateDirectory = __DIR__ . '/../view/';
    private $tempDirectory = ABSPATH . 'tmp/';
    private $file = __FILE__;
    private $Path;
    private $url;

    static public function init()
    {
        $instance = static::load();
        $instance->prepare();

        if (!$instance->checkDependencies()) {
            return;
        }

        if (0 == strlen($instance->getPluginConfiguration()) || !class_exists($instance->getPluginConfiguration())) {
            throw new \RuntimeException('Please provide a plugin configuration');
        }

        $instance->loadPlugin(
            $instance->getSlug() ,
            $instance->getTempDirectory(),
            $instance->getPluginConfiguration()
        );
    }

    /**
     * @return Init
     */
    static public function load(): Init
    {
        return static::getInstance(static::class);
    }

    /**
     * @param string $key
     * @return Init
     */
    static protected function getInstance(string $key): Init
    {
        if (isset(static::$instances[$key])) {
            return static::$instances[$key];
        }
        static::$instances[$key] = new $key();
        return static::$instances[$key];
    }

    /**
     * Prepare environmental information
     */
    protected function prepare()
    {
        $this->setPluginConfiguration(static::PLUGIN_CONFIGURATION);
        $this->setVersion(static::VERSION);
        $this->setSlug(static::SLUG);
        $this->setTextdomain(static::SLUG);
        $this->setTemplateDirectory(static::TEMPLATE_DIR);
        $this->setTempDirectory(static::TEMP_DIR);
        $this->setFile(static::FILE);
        $this->setPath(\plugin_dir_path(static::FILE));
        $this->setUrl(\plugin_dir_url(static::FILE));
    }

    /**
     * @return void
     */
    protected function loadPlugin(string $pluginSlug, string $cacheFolder, string $configurationClassName): void
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
    protected function checkDependencies(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getPluginConfiguration(): string
    {
        return $this->pluginConfiguration;
    }

    /**
     * @param string $pluginConfiguration
     * @return Init
     */
    public function setPluginConfiguration(string $pluginConfiguration): Init
    {
        $this->pluginConfiguration = $pluginConfiguration;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     * @return Init
     */
    public function setVersion(string $version): Init
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return Init
     */
    public function setSlug(string $slug): Init
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getTextdomain(): string
    {
        return $this->textdomain;
    }

    /**
     * @param string $textdomain
     * @return Init
     */
    public function setTextdomain(string $textdomain): Init
    {
        $this->textdomain = $textdomain;
        return $this;
    }

    /**
     * @return string
     */
    public function getTemplateDirectory(): string
    {
        return $this->templateDirectory;
    }

    /**
     * @param string $templateDirectory
     * @return Init
     */
    public function setTemplateDirectory(string $templateDirectory): Init
    {
        $this->templateDirectory = $templateDirectory;
        return $this;
    }

    /**
     * @return string
     */
    public function getTempDirectory(): string
    {
        return $this->tempDirectory;
    }

    /**
     * @param string $tempDirectory
     * @return Init
     */
    public function setTempDirectory(string $tempDirectory): Init
    {
        $this->tempDirectory = $tempDirectory;
        return $this;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @param string $file
     * @return Init
     */
    public function setFile(string $file): Init
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->Path;
    }

    /**
     * @param mixed $Path
     * @return Init
     */
    public function setPath($Path)
    {
        $this->Path = $Path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return Init
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }
}
