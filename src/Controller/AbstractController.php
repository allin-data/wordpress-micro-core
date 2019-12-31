<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Controller;

use AllInData\Micro\Core\Helper\RequestUtil;
use AllInData\Micro\Core\Init;
use AllInData\Micro\Core\Model\AbstractModel;
use Exception;

/**
 * Class AbstractController
 * @package AllInData\Micro\Core\Controller
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
        $result = $this->doExecute();
        $this->afterExecute($result);
    }

    /**
     * Tasks before execution
     * @throws Exception
     */
    protected function beforeExecute()
    {
        if (!$this->isAllowed()) {
            $this->throwErrorMessage(__('Insufficient permissions', Init::$TEXTDOMAIN));
        }

        if (!(defined('DOING_AJAX') && DOING_AJAX)) {
            $nonce = $this->getParam('nonce');
            if (!$nonce || false === wp_verify_nonce($nonce, static::ACTION_SLUG)) {
                $this->throwErrorMessage(__('Invalid nonce specified', Init::$TEXTDOMAIN));
            }
        }
    }

    /**
     * Tasks for action execution
     */
    abstract protected function doExecute();

    /**
     * Tasks after execution
     * @param mixed|AbstractModel|null $result
     */
    protected function afterExecute($result = null)
    {
        // stop if controller action is used async
        if ((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ||
            (defined('DOING_AJAX') && DOING_AJAX)) {
            if ($result && $result instanceof AbstractModel) {
                echo json_encode($result->toArray());
            } elseif ($result) {
                echo json_encode($result);
            }
            wp_die();
        }

        $redirectTarget = $this->getParam(static::REDIRECTION_KEY);
        if ($redirectTarget) {
            wp_redirect($redirectTarget, 302, null);
            exit();
        }
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @param int $filterType
     * @return mixed|null
     */
    protected function getParam($key, $defaultValue = null, $filterType = FILTER_SANITIZE_STRING)
    {
        return RequestUtil::getParam($key, $defaultValue, $filterType);
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @param int $filterType
     * @return array|null
     */
    protected function getParamAsArray($key, $defaultValue = null, $filterType = FILTER_DEFAULT)
    {
        return RequestUtil::getParamAsArray($key, $defaultValue, $filterType);
    }

    /**
     * Check if current user session is allowed to execute controller action
     * @return bool
     */
    protected function isAllowed(): bool
    {
        if (!is_user_logged_in() ||
            !$this->hasAllowanceCapabilities()) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function hasAllowanceCapabilities(): bool
    {
        if (current_user_can('administrator')) {
            return true;
        }

        if (empty($this->getRequiredCapabilitySet())) {
            return true;
        }

        foreach ($this->getRequiredCapabilitySet() as $requiredCapability) {
            if (current_user_can($requiredCapability)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    protected function getRequiredCapabilitySet(): array
    {
        return [];
    }

    /**
     * @param string|int $userId
     * @return bool
     */
    protected function isCurrentUser($userId): bool
    {
        return $userId == get_current_user_id();
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