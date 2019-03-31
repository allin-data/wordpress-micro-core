<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\Model;

/**
 * Class AbstractModel
 * @package AllInData\Dgr\Core\Model
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
        $methodName = sprintf('get%s',  $this->canonicalizeMethodName($name));
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
        $methodName = sprintf('set%s',  $this->canonicalizeMethodName($name));
        if (!method_exists($this, $methodName)) {
            $this->{$name} = $value;
            return $this;
        }
        $this->{$methodName}($value);
        return $this;
    }

    /**
     * @param string $name
     * @return mixed|int|double|float|bool|string|object|array|null
     */
    public function get($name)
    {
        $methodName = sprintf('get%s',  $this->canonicalizeMethodName($name));
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
        $methodName = sprintf('set%s',  $this->canonicalizeMethodName($name));
        if (!method_exists($this, $methodName)) {
            $this->{$name} = $value;
            return $this;
        }
        $this->{$methodName}($value);
        return $this;
    }

    /**
     * @param array $dataSet
     * @return $this
     */
    public function fromArray(array $dataSet)
    {
        foreach ($dataSet as $key => $value) {
            $methodName = sprintf('set%s',  $this->canonicalizeMethodName($key));
            if (!method_exists($this, $methodName)) {
                $this->{$key} = $value;
                continue;
            }
            $this->{$methodName}($value);
        }
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $dataSet = get_object_vars($this);
        foreach ($dataSet as $idx => $value) {
            if ($value instanceof AbstractModel) {
                $dataSet[$idx] = $value->toArray();
            } elseif (is_object($value)) {
                $dataSet[$idx] = json_decode(json_encode($value), true);
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
        $methodNameParts = explode(' ', ucwords(str_replace(['-','_'], ' ', $methodName)));
        foreach ($methodNameParts as $idx => $part) {
            $methodNameParts[$idx] = ucfirst(strtolower($part));
        }

        return implode('', $methodNameParts);
    }
}
