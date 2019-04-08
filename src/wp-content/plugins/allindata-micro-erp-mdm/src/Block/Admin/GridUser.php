<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Block\Admin;

use AllInData\MicroErp\Mdm\Controller\Admin\CreateUser;
use AllInData\MicroErp\Mdm\Controller\Admin\UpdateUser;
use AllInData\MicroErp\Mdm\Model\Collection\User as UserCollection;
use AllInData\MicroErp\Mdm\Model\User;
use AllInData\MicroErp\Core\Block\AbstractBlock;

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
     * @return int
     */
    public function getUserTotalCount()
    {
        return $this->userCollection->getTotalCount();
    }

    /**
     * @return string
     */
    public function getCreateUserActionSlug()
    {
        return CreateUser::ACTION_SLUG;
    }

    /**
     * @return string
     */
    public function getUpdateUserActionSlug()
    {
        return UpdateUser::ACTION_SLUG;
    }
}