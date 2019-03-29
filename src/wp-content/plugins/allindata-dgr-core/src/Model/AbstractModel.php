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
        return $this->{$name} ?: null;
    }

    /**
     * @param string $name
     * @param mixed|null $value
     * @return $this
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
        return $this;
    }

    /**
     * @param string $name
     * @return mixed|int|double|float|bool|string|object|array|null
     */
    public function get($name)
    {
        return $this->{$name} ?: null;
    }

    /**
     * @param string $name
     * @param mixed|null $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->{$name} = $value;
        return $this;
    }

    /**
     * @param array $dataSet
     * @return $this
     */
    public function fromArray(array $dataSet)
    {
        foreach ($dataSet as $key => $value) {
            $methodName = sprintf('set%s', ucfirst($key));
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
}
