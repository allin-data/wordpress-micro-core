<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Subordination\Helper;

use AllInData\MicroErp\Subordination\Model\User;
use AllInData\MicroErp\Mdm\Model\Resource\User as UserResource;

/**
 * Class CurrentUserScope
 * @package AllInData\MicroErp\Subordination\Helper
 */
class CurrentUserScope
{
    /**
     * @var UserResource
     */
    private $userResource;

    /**
     * @return User
     */
    public function getUser(): User
    {
        /** @var User $user */
        $user = $this->userResource->loadById(get_current_user_id());
        return $user;
    }

    /**
     * @return int
     */
    public function getCurrentUserScopeId(): int
    {
        return $this->getUser()->getCurrentUserScopeId();
    }

    /**
     * @return User
     */
    public function getCurrentUserScopeInstance(): User
    {
        /** @var User $user */
        $user = $this->userResource->loadById($this->getCurrentUserScopeId());
        return $user;
    }

    /**
     * @return bool
     */
    public function hasUserScopedId(): bool
    {
        $user = $this->getUser();
        return $user->getId() == $user->getCurrentUserScopeId();
    }
}