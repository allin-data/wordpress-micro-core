<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Block\Admin;

use AllInData\Dgr\Cms\Controller\Admin\CreateUser;
use AllInData\Dgr\Cms\Controller\Admin\UpdateUser;
use AllInData\Dgr\Cms\Model\Collection\User as UserCollection;
use AllInData\Dgr\Cms\Model\User;
use AllInData\Dgr\Core\Block\AbstractBlock;

/**
 * Class GridUser
 * @package AllInData\Dgr\Cms\Block\Admin
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