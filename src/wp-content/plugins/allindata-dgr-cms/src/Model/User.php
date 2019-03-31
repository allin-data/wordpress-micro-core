<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Cms\Model;

use AllInData\Dgr\Core\Model\AbstractModel;

/**
 * Class User
 * @package AllInData\Dgr\Cms\Model
 */
class User extends AbstractModel
{
    /**
     * @var int|string|null
     */
    private $id;
    /**
     * @var string|null
     */
    private $firstName;
    /**
     * @var string|null
     */
    private $lastName;
    /**
     * @var string|null
     */
    private $displayName;
    /**
     * @var string|null
     */
    private $userLogin;
    /**
     * @var string|null
     */
    private $userPass;
    /**
     * @var string|null
     */
    private $userEmail;

    /**
     * @return int|string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|string|null $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return User
     */
    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     * @return User
     */
    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    /**
     * @param string|null $displayName
     * @return User
     */
    public function setDisplayName(?string $displayName): User
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserLogin(): ?string
    {
        return $this->userLogin;
    }

    /**
     * @param string|null $userLogin
     * @return User
     */
    public function setUserLogin(?string $userLogin): User
    {
        $this->userLogin = $userLogin;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserPass(): ?string
    {
        return $this->userPass;
    }

    /**
     * @param string|null $userPass
     * @return User
     */
    public function setUserPass(?string $userPass): User
    {
        $this->userPass = $userPass;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    /**
     * @param string|null $userEmail
     * @return User
     */
    public function setUserEmail(?string $userEmail): User
    {
        $this->userEmail = $userEmail;
        return $this;
    }
}