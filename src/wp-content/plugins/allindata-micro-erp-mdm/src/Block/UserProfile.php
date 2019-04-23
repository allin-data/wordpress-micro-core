<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Block;

use AllInData\MicroErp\Mdm\Controller\UpdateUser;
use AllInData\MicroErp\Mdm\Model\Resource\User as UserResource;
use AllInData\MicroErp\Mdm\Model\User;
use AllInData\MicroErp\Core\Block\AbstractBlock;

/**
 * Class UserProfile
 * @package AllInData\MicroErp\Mdm\Block
 */
class UserProfile extends AbstractBlock
{
    /**
     * @var UserResource
     */
    private $userResource;

    /**
     * UserProfile constructor.
     * @param UserResource $userResource
     */
    public function __construct(UserResource $userResource)
    {
        $this->userResource = $userResource;
    }

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
     * @return string
     */
    public function getUpdateUserActionSlug()
    {
        return UpdateUser::ACTION_SLUG;
    }
}