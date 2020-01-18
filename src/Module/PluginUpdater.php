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
    private $installedFolder;

    /**
     * PluginUpdater constructor.
     * @param string $slug
     * @param string $remoteDeployJson
     * @param string $installedVersion
     * @param string $installedFolder
     */
    public function __construct(
        string $slug,
        string $remoteDeployJson,
        string $installedVersion,
        string $installedFolder
    ) {
        $this->slug = $slug;
        $this->remoteDeployJson = $remoteDeployJson;
        $this->installedVersion = $installedVersion;
        $this->installedFolder = $installedFolder;
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        \add_filter('plugins_api', [$this, 'applyUpdateCheck'], 20, 3);
        \add_filter('site_transient_update_plugins', [$this, 'applyUpdate']);
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
                \set_transient($transientSlug, $remote, 43200); // 12 hours cache
            }

        }


        if (!$remote) {
            return $transient;
        }

        $remote = \json_decode($remote['body']);
        // your installed plugin version should be on the line below! You can obtain it dynamically of course
        if ($remote &&
            version_compare($this->installedVersion, $remote->version, '<') &&
            version_compare($remote->requires, get_bloginfo('version'), '<')) {
            $res = new \stdClass();
            $res->slug = $this->slug;
            $res->plugin = $this->installedFolder;
            $res->new_version = $remote->version;
            $res->tested = $remote->tested;
            $res->package = $remote->download_url;
            $transient->response[$res->plugin] = $res;
            $transient->checked[$res->plugin] = $remote->version;
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

        $remote = \json_decode($remote['body']);;
        return $remote;
    }
}