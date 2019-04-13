<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class AbstractModel
 * @package AllInData\MicroErp\Core\Model
 */
abstract class AbstractModel
{
    /**
     * AbstractModel constructor.
     * @param array $dataSet
     */
    public function __construct(array $dataSet = [])
    {
        $this->fromArray($dataSet);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        $methodName = sprintf('get%s', $this->canonicalizeMethodName($name));
        if (!method_exists($this, $methodName)) {
            return $this->{$name} ?: null;
        }
        return $this->{$methodName}();
    }

    /**
     * @param string $name
     * @param mixed|null $value
     * @return $this
     */
    public function __set($name, $value)
    {
        $methodName = sprintf('set%s', $this->canonicalizeMethodName($name));
        if (!method_exists($this, $methodName)) {
            $this->{$name} = $value;
            return $this;
        }

        $this->{$methodName}($this->castValue($value));
        return $this;
    }

    /**
     * @param string $name
     * @return mixed|int|double|float|bool|string|object|array|null
     */
    public function get($name)
    {
        $methodName = sprintf('get%s', $this->canonicalizeMethodName($name));
        if (!method_exists($this, $methodName)) {
            return $this->{$name} ?: null;
        }
        return $this->{$methodName}();
    }

    /**
     * @param string $name
     * @param mixed|null $value
     * @return $this
     */
    public function set($name, $value)
    {
        $methodName = sprintf('set%s', $this->canonicalizeMethodName($name));
        if (!method_exists($this, $methodName)) {
            $this->{$name} = $value;
            return $this;
        }
        $this->{$methodName}($this->castValue($value));
        return $this;
    }

    /**
     * @param array $dataSet
     * @return $this
     */
    public function fromArray(array $dataSet)
    {
        foreach ($dataSet as $key => $value) {
            $methodName = sprintf('set%s', $this->canonicalizeMethodName($key));
            if (!method_exists($this, $methodName)) {
                $this->{$key} = $value;
                continue;
            }
            $this->{$methodName}($this->castValue($value));
        }
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        try {
            $reflect = new ReflectionClass($this);
        } catch (ReflectionException $e) {
            return [];
        }
        $properties = $reflect->getProperties(
            ReflectionProperty::IS_PUBLIC |
            ReflectionProperty::IS_PROTECTED |
            ReflectionProperty::IS_PRIVATE
        );
        $dataSet = [];
        foreach ($properties as $property) {
            $methodName = sprintf('get%s', ucfirst($property->getName()));
            $value = $this->{$methodName}();
            if ($value instanceof AbstractModel) {
                $dataSet[$property->getName()] = $value->toArray();
            } elseif (is_object($value)) {
                $dataSet[$property->getName()] = json_decode(json_encode($value), true);
            } else {
                $dataSet[$property->getName()] = $value;
            }

            continue;
        }

        return $dataSet;
    }

    /**
     * @param string $methodName
     * @return string
     */
    private function canonicalizeMethodName(string $methodName): string
    {
        $methodNameParts = explode(' ', ucwords(str_replace(['-', '_'], ' ', $methodName)));
        foreach ($methodNameParts as $idx => $part) {
            $methodNameParts[$idx] = ucfirst(strtolower($part));
        }

        return implode('', $methodNameParts);
    }

    /**
     * @param mixed $value
     * @return array|bool|float|int|string|null
     */
    private function castValue($value)
    {
        if (is_array($value)) {
            return (array)$value;
        }
        if (!!$value === $value) {
            return !!$value;
        }
        if (is_numeric($value) && intval($value) == $value) {
            return (int)$value;
        }
        if (is_numeric($value) && floatval($value) == $value) {
            return (float)$value;
        }
        if (is_null($value)) {
            return null;
        }
        if (is_string($value)) {
            return (string)$value;
        }
        return $value;
    }
}
