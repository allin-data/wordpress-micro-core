<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

use InvalidArgumentException;

/**
 * Class AbstractValidator
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractValidator
{
    /**
     * @var string
     */
    private $modelClass;
    /**
     * @var string[]
     */
    private $errorList = [];

    /**
     * AbstractFactory constructor.
     * @param string $modelClass
     */
    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @param AbstractModel $model
     * @return $this
     */
    public function validate(AbstractModel $model): AbstractValidator
    {
        if (!(class_exists($this->modelClass))) {
            throw new InvalidArgumentException(
                sprintf('Class "%s" does not exist or could not be found.', $this->modelClass)
            );
        }

        if (!($model instanceof $this->modelClass)) {
            throw new InvalidArgumentException(
                sprintf('Instance is no object of "%s".', $this->modelClass)
            );
        }

        $this->errorList = [];
        $this->doValidation($model);
        return $this;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return empty($this->errorList);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errorList;
    }

    /**
     * @param string $message
     */
    protected function addError(string $message)
    {
        $this->errorList[] = $message;
    }

    /**
     * @param AbstractModel $model
     */
    abstract protected function doValidation(AbstractModel $model);
}
