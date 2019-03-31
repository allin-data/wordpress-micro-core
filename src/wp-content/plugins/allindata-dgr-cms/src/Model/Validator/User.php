<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model\Validator;

use AllInData\Dgr\Core\Model\AbstractModel;
use AllInData\Dgr\Core\Model\AbstractValidator;

/**
 * Class User
 * @package AllInData\Dgr\Cms\Validator
 */
class User extends AbstractValidator
{
    /**
     * @param AbstractModel $model
     */
    protected function doValidation(AbstractModel $model)
    {
        /** @var \AllInData\Dgr\Cms\Model\User $model */
        if (empty($model->getFirstName())) {
            $this->addError(__('First name is empty', AID_DGR_CMS_TEXTDOMAIN));
        }

        if (empty($model->getLastName())) {
            $this->addError(__('Last name is empty', AID_DGR_CMS_TEXTDOMAIN));
        }
    }
}