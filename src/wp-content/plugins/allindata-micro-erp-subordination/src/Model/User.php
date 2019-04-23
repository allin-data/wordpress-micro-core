<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Subordination\Model;

/**
 * Class User
 * @package AllInData\MicroErp\Subordination\Model
 */
class User extends \AllInData\MicroErp\Mdm\Model\User
{
    /**
     * @var int|string|null
     */
    private $currentUserScopeId;

    /**
     * @return int|string|null
     */
    public function getCurrentUserScopeId()
    {
        return $this->currentUserScopeId;
    }

    /**
     * @param int|string|null $currentUserScopeId
     * @return User
     */
    public function setCurrentUserScopeId($currentUserScopeId)
    {
        $this->currentUserScopeId = $currentUserScopeId;
        return $this;
    }
}