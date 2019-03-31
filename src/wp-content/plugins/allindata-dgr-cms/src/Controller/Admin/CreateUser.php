<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Controller\Admin;

use AllInData\Dgr\Cms\Model\User;
use AllInData\Dgr\Cms\Model\UserRole;
use AllInData\Dgr\Cms\Model\Validator\User as UserValidator;
use AllInData\Dgr\Cms\Model\Resource\User as UserResource;
use AllInData\Dgr\Core\Controller\AbstractAdminController;
use function wp_create_user;

/**
 * Class CreateUser
 * @package AllInData\Dgr\Cms\Controller
 */
class CreateUser extends AbstractAdminController
{
    const ACTION_SLUG = 'dgr_admin_create_user';
    const USER_PASSWORD_LENGTH = 32;

    /**
     * @var UserValidator
     */
    private $userValidator;
    /**
     * @var UserResource
     */
    private $userResource;

    /**
     * CreateUser constructor.
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
        $firstName = $this->getParam('firstName');
        $lastName = $this->getParam('lastName');
        $login = $this->getParam('login');
        $email = $this->getParam('email');
        /** @var string $password */
        $password = wp_generate_password(self::USER_PASSWORD_LENGTH, true, true);
        $userId = wp_create_user($login, $password, $email);
        $nativeUser = new \WP_User($userId);
        $nativeUser->set_role(UserRole::ROLE_LEVEL_USER_DEFAULT);

        if (!is_int($userId)) {
            if ($userId instanceof \WP_Error) {
                $this->throwErrorMessage($userId->get_error_message());
            }
            $this->throwErrorMessage(__('Failed to create user', AID_DGR_CMS_TEXTDOMAIN));
        }

        /** @var User $user */
        $user = $this->userResource->loadById($userId);
        $user->setFirstName($firstName)
            ->setLastName($lastName)
            ->setDisplayName(sprintf('%s %s', $firstName, $lastName));
        if (!$this->userValidator->validate($user)->isValid()) {
            $this->throwErrorMessage(implode(',', $this->userValidator->getErrors()));
        }

        $this->userResource->save($user);
    }
}