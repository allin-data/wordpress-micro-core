<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Block\Admin;

use AllInData\MicroErp\Mdm\Controller\Admin\UpdateUser;
use AllInData\MicroErp\Mdm\Model\Collection\User as UserCollection;
use AllInData\MicroErp\Mdm\Model\User;
use AllInData\MicroErp\Core\Block\AbstractBlock;
use WP_User;

/**
 * Class GridUser
 * @package AllInData\MicroErp\Mdm\Block\Admin
 */
class GridUser extends AbstractBlock
{
    /**
     * @var UserCollection
     */
    private $userCollection;

    /**
     * GridUser constructor.
     * @param UserCollection $userCollection
     */
    public function __construct(UserCollection $userCollection)
    {
        $this->userCollection = $userCollection;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return User[]
     */
    public function getUsers($limit = UserCollection::DEFAULT_LIMIT, $offset = UserCollection::DEFAULT_OFFSET): array
    {
        return $this->userCollection->load($limit, $offset);
    }

    /**
     * @param User $user
     * @return string
     */
    public function getUserRoles(User $user): string
    {
        global $wp_roles;
        $wpUser = new WP_User($user->getId());
        if (empty($wpUser->roles)) {
            return __('No role applied', AID_MICRO_ERP_MDM_TEXTDOMAIN);
        }

        $roleLabelSet = [];
        foreach ($wpUser->roles as $roleId) {
            $roleLabelSet[] = translate_user_role($wp_roles->roles[$roleId]['name']);
        }

        return rtrim(implode(', ', $roleLabelSet), ', ');
    }

    /**
     * @return int
     */
    public function getUserTotalCount()
    {
        return $this->userCollection->getTotalCount();
    }

    /**
     * @return string
     */
    public function getUpdateUserActionSlug()
    {
        return UpdateUser::ACTION_SLUG;
    }
}