<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Controller\Admin;

use AllInData\MicroErp\Mdm\Model\User;
use AllInData\MicroErp\Mdm\Model\Validator\User as UserValidator;
use AllInData\MicroErp\Mdm\Model\Resource\User as UserResource;
use AllInData\MicroErp\Core\Controller\AbstractAdminController;

/**
 * Class UpdateUser
 * @package AllInData\MicroErp\Mdm\Controller\Admin
 */
class UpdateUser extends AbstractAdminController
{
    const ACTION_SLUG = 'micro_erp_mdm_admin_user_update';

    /**
     * @var UserValidator
     */
    private $userValidator;
    /**
     * @var UserResource
     */
    private $userResource;

    /**
     * UpdateUser constructor.
     * @param UserValidator $userValidator
     * @param UserResource $userResource
     */
    public function __construct(UserValidator $userValidator, UserResource $userResource)
    {
        $this->userValidator = $userValidator;
        $this->userResource = $userResource;
    }

    /**
     * @inheritDoc
     */
    protected function doExecute()
    {
        $userId = $this->getParam('userId');
        $firstName = $this->getParam('firstName');
        $lastName = $this->getParam('lastName');
        $email = $this->getParam('email');

        /** @var User $user */
        $user = $this->userResource->loadById($userId);
        if (!$user->getId()) {
            $this->throwErrorMessage(sprintf(__('User with id "%s" does not exist', AID_MICRO_ERP_MDM_TEXTDOMAIN), $userId));
        }

        $user->setUserEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setDisplayName(sprintf('%s %s', $firstName, $lastName));
        if (!$this->userValidator->validate($user)->isValid()) {
            $this->throwErrorMessage(implode(',', $this->userValidator->getErrors()));
        }

        $this->userResource->save($user);
    }
}