<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Controller;

use AllInData\MicroErp\Mdm\Model\UserRole;
use Exception;

/**
 * Class AbstractController
 * @package AllInData\MicroErp\Core\Controller
 */
abstract class AbstractController implements PluginControllerInterface
{
    const ACTION_SLUG = '';
    const REDIRECTION_KEY = 'redirect_target';

    /**
     * @inheritdoc
     */
    public function init()
    {
        add_action('admin_post_' . static::ACTION_SLUG, [$this, 'execute']);
        add_action('wp_ajax_' . static::ACTION_SLUG, [$this, 'execute']);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $this->beforeExecute();
        $this->doExecute();
        $this->afterExecute();
    }

    /**
     * Tasks before execution
     * @throws Exception
     */
    protected function beforeExecute()
    {
        if (!$this->isAllowed()) {
            $this->throwErrorMessage(__('Insufficient permissions', AID_MICRO_ERP_CORE_TEXTDOMAIN));
        }

        if (!(defined('DOING_AJAX') && DOING_AJAX)) {
            $nonce = $this->getParam('nonce');
            if (!$nonce || false === wp_verify_nonce($nonce, static::ACTION_SLUG)) {
                $this->throwErrorMessage(__('Invalid nonce specified', AID_MICRO_ERP_CORE_TEXTDOMAIN));
            }
        }
    }

    /**
     * Tasks for action execution
     */
    abstract protected function doExecute();

    /**
     * Tasks after execution
     */
    protected function afterExecute()
    {
        // stop if controller action is used async
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            wp_die();
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            wp_die();
        }

        $redirectTarget = $this->getParam(static::REDIRECTION_KEY);
        if ($redirectTarget) {
            wp_safe_redirect(site_url($redirectTarget));
            wp_die();
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @param int $filterType
     * @return mixed|null
     */
    protected function getParam($key, $defaultValue = null, $filterType = FILTER_SANITIZE_STRING)
    {
        $val = $this->getGetParam($key, $defaultValue, $filterType);
        if (is_null($val)) {
            $val = $this->getPostParam($key, $defaultValue, $filterType);
        }
        return $val;
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @param int $filterType
     * @return mixed|null
     */
    protected function getGetParam($key, $defaultValue = null, $filterType = FILTER_SANITIZE_STRING)
    {
        $val = filter_input(INPUT_GET, $key, $filterType);
        if (is_null($val)) {
            return $defaultValue;
        }
        return $val;
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @param int $filterType
     * @return mixed|null
     */
    protected function getPostParam($key, $defaultValue = null, $filterType = FILTER_SANITIZE_STRING)
    {
        $val = filter_input(INPUT_POST, $key, $filterType);
        if (is_null($val)) {
            return $defaultValue;
        }
        return $val;
    }

    /**
     * Check if current user session is allowed to execute controller action
     * @return bool
     */
    protected function isAllowed(): bool
    {
        if (!is_user_logged_in() ||
            !(current_user_can(UserRole::ROLE_LEVEL_USER_DEFAULT) ||
                current_user_can(UserRole::ROLE_LEVEL_ADMINISTRATION))) {
            return false;
        }
        return true;
    }

    /**
     * @param string $message
     * @throws Exception
     */
    protected function throwErrorMessage(string $message)
    {
        if (true === WP_DEBUG || true === WP_DEBUG_DISPLAY) {
            trigger_error(sprintf(
                '[%s::Error] %s',
                static::class,
                $message
            ));
        }

        if (true === WP_DEBUG_LOG) {
            $logPath = ABSPATH . 'wp-content/logs/';
            $logFile = $logPath . 'debug.log';

            if (!is_dir($logPath)) {
                mkdir($logPath, 0770, true);
            }

            error_log(
                $message . PHP_EOL,
                3,
                $logFile
            );
        }


        error_log(
            '[' . static::class . '::throwErrorMessage] ' . $message,
            0
        );

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            wp_die();
        }
        if (defined('DOING_AJAX') && DOING_AJAX) {
            wp_die();
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        throw new Exception($message);
    }
}