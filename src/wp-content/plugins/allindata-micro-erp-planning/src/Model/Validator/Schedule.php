<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Model\Validator;

use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Core\Model\AbstractValidator;

/**
 * Class User
 * @package AllInData\MicroErp\Mdm\Validator
 */
class Schedule extends AbstractValidator
{
    /**
     * @param AbstractModel $model
     */
    protected function doValidation(AbstractModel $model)
    {
        /** @var \AllInData\MicroErp\Planning\Model\Schedule $model */
    }
}