<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Validator;

use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Core\Model\AbstractValidator;

/**
 * Class User
 * @package AllInData\MicroErp\Mdm\Validator
 */
class User extends AbstractValidator
{
    /**
     * @param AbstractModel $model
     */
    protected function doValidation(AbstractModel $model)
    {
        /** @var \AllInData\MicroErp\Mdm\Model\User $model */
        if (empty($model->getFirstName())) {
            $this->addError(__('First name is empty', AID_MICRO_ERP_MDM_TEXTDOMAIN));
        }

        if (empty($model->getLastName())) {
            $this->addError(__('Last name is empty', AID_MICRO_ERP_MDM_TEXTDOMAIN));
        }
    }
}