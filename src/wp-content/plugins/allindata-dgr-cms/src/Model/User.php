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
     * @var int|null
     */
    private $ID;
    /**
     * @var string|null
     */
    private $firstName;
    /**
     * @var string|null
     */
    private $lastName;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->ID;
    }

    /**
     * @param int|null $ID
     * @return User
     */
    public function setId(?int $ID): User
    {
        $this->ID = $ID;
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
}