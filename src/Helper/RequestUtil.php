<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Helper;

/**
 * Class RequestUtil
 * @package AllInData\Micro\Core\Helper
 */
class RequestUtil
{
    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @param int $filterType
     * @return mixed|null
     */
    static public function getParam($key, $defaultValue = null, $filterType = FILTER_SANITIZE_STRING)
    {
        $val = static::getGetParam($key, null, $filterType);
        if (is_null($val)) {
            $val = static::getPostParam($key, $defaultValue, $filterType);
        }
        return $val;
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @param int $filterType
     * @return array|null
     */
    static public function getParamAsArray($key, $defaultValue = null, $filterType = FILTER_DEFAULT)
    {
        $val = static::getGetParamAsArray($key, null, $filterType);
        if (empty($val)) {
            $val = static::getPostParamAsArray($key, $defaultValue, $filterType);
        }
        return $val;
    }

    /**
     * @param string $key
     * @param null|mixed $defaultValue
     * @param int $filterType
     * @return mixed|null
     */
    static public function getGetParam($key, $defaultValue = null, $filterType = FILTER_SANITIZE_STRING)
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
    static public function getPostParam($key, $defaultValue = null, $filterType = FILTER_SANITIZE_STRING)
    {
        $val = filter_input(INPUT_POST, $key, $filterType);
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
    static public function getGetParamAsArray($key, $defaultValue = null, $filterType = FILTER_DEFAULT)
    {
        $val = filter_input(INPUT_GET, $key, $filterType, FILTER_FORCE_ARRAY);
        if (empty($val)) {
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
    static public function getPostParamAsArray($key, $defaultValue = null, $filterType = FILTER_DEFAULT)
    {
        $val = filter_input(INPUT_POST, $key, $filterType, FILTER_FORCE_ARRAY);
        if (empty($val)) {
            return $defaultValue;
        }
        return $val;
    }
}