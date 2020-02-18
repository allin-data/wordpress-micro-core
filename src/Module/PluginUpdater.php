<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Module;

/**
 * Class PluginUpdater
 * @package AllInData\Micro\Core\Module
 */
class PluginUpdater implements PluginModuleInterface
{
    const TRANSIENT_SUFFIX = 'allindata_micro_core_';

    /**
     * @var string
     */
    private $slug;
    /**
     * @var string
     */
    private $remoteDeployJson;
    /**
     * @var string
     */
    private $installedVersion;
    /**
     * @var string
     */
    private $destinationFolder;
    /**
     * @var string
     */
    private $installedPlugin;

    /**
     * PluginUpdater constructor.
     * @param string $slug
     * @param string $remoteDeployJson
     * @param string $installedVersion
     * @param string $destinationFolder
     * @param string $installedPlugin
     */
    public function __construct(
        string $slug,
        string $remoteDeployJson,
        string $installedVersion,
        string $destinationFolder,
        string $installedPlugin = null
    ) {
        $this->slug = $slug;
        $this->remoteDeployJson = $remoteDeployJson;
        $this->installedVersion = $installedVersion;
        $this->destinationFolder = $destinationFolder;
        $this->installedPlugin = $installedPlugin;
        if (!$this->installedPlugin) {
            $this->installedPlugin = $this->slug . '/' . $this->slug . '.php';
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        \add_filter('plugins_api', [$this, 'applyUpdateCheck'], 20, 3);
        \add_filter('pre_set_site_transient_update_plugins', [$this, 'applyUpdate']);
        \add_filter('upgrader_package_options', [$this, 'applyUpdateOptions']);
        \add_filter('upgrader_process_complete', [$this, 'clearCache'], 10, 2);
    }

    /**
     * @param $upgraderObject
     * @param $options
     */
    public function clearCache($upgraderObject, $options)
    {
        $transientSlug = sprintf('%s%s', static::TRANSIENT_SUFFIX, $this->slug);
        if ($options['action'] == 'update' && $options['type'] === 'plugin') {
            delete_transient($transientSlug);
        }
    }

    /**
     * @param array $options
     * @return array
     */
    public function applyUpdateOptions($options)
    {
        if (!isset($options['hook_extra']) ||
            !isset($options['hook_extra']['plugin']) ||
            $options['hook_extra']['plugin'] !== $this->installedPlugin) {
            return $options;
        }

        $options['destination'] = $this->destinationFolder;
        $options['clear_destination'] = true;
        return $options;
    }

    /**
     * @param \stdClass $transient
     * @return \stdClass
     */
    public function applyUpdate($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $transientSlug = sprintf('%s%s', static::TRANSIENT_SUFFIX, $this->slug);
        if (false == $remote = get_transient($transientSlug)) {
            $remote = \wp_remote_get($this->remoteDeployJson, array(
                    'timeout' => 10,
                    'headers' => array(
                        'Accept' => 'application/json'
                    )
                )
            );

            if (!\is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {
                \set_transient($transientSlug, $remote, 43200);
            }
        }

        if (!$remote) {
            return $transient;
        }

        $remote = \json_decode($remote['body']);
        if ($remote &&
            version_compare($this->installedVersion, $remote->version, '<') &&
            version_compare($remote->requires, \get_bloginfo('version'), '<')) {
            $remote->new_version = $remote->version;
            $remote->package = $remote->download_url;
            $transient->response[$this->installedPlugin] = $remote;
        }

        return $transient;
    }

    /**
     * Apply update check
     * @param false|object|array $result
     * @param string $action
     * @param array|object $args
     * @return false|object|array
     */
    public function applyUpdateCheck($result, string $action, $args)
    {
        // do nothing if this is not about getting plugin information
        if ($action !== 'plugin_information' ||
            $this->slug !== $args->slug) {
            return $result;
        }

        $transientSlug = sprintf('%s%s', static::TRANSIENT_SUFFIX, $this->slug);
        if (false == $remote = \get_transient($transientSlug)) {
            $remote = \wp_remote_get($this->remoteDeployJson, array(
                    'timeout' => 10,
                    'headers' => array(
                        'Accept' => 'application/json'
                    )
                )
            );

            if (!\is_wp_error($remote) && isset($remote['response']['code']) && $remote['response']['code'] == 200 && !empty($remote['body'])) {
                \set_transient($transientSlug, $remote, 43200);
            }
        }

        if (!$remote) {
            return false;
        }

        $remote = \json_decode($remote['body'], true);
        return $remote;
    }
}