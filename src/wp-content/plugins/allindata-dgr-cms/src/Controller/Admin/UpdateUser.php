<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Controller\Admin;

use AllInData\Dgr\Cms\Model\User;
use AllInData\Dgr\Cms\Model\Validator\User as UserValidator;
use AllInData\Dgr\Cms\Model\Resource\User as UserResource;
use AllInData\Dgr\Core\Controller\AbstractAdminController;

/**
 * Class UpdateUser
 * @package AllInData\Dgr\Cms\Controller\Admin
 */
class UpdateUser extends AbstractAdminController
{
    const ACTION_SLUG = 'dgr_admin_update_user';

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
        /** @var string $password */

        /** @var User $user */
        $user = $this->userResource->loadById($userId);
        if (!$user->getId()) {
            $this->throwErrorMessage(sprintf(__('User with id "%s" does not exist', AID_DGR_CMS_TEXTDOMAIN), $userId));
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