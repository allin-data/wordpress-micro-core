<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Planning\Model;

use AllInData\MicroErp\Core\Model\AbstractModel;

/**
 * Class ScheduleMetaCreator
 * @package AllInData\MicroErp\Planning\Model
 */
class ScheduleMetaCreator extends AbstractModel
{
    /**
     * @var string|null
     */
    private $name;
    /**
     * @var string|null
     */
    private $avatar;
    /**
     * @var string|null
     */
    private $company;
    /**
     * @var string|null
     */
    private $email;
    /**
     * @var string|null
     */
    private $phone;

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return ScheduleMetaCreator
     */
    public function setName(?string $name): ScheduleMetaCreator
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * @param string|null $avatar
     * @return ScheduleMetaCreator
     */
    public function setAvatar(?string $avatar): ScheduleMetaCreator
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * @param string|null $company
     * @return ScheduleMetaCreator
     */
    public function setCompany(?string $company): ScheduleMetaCreator
    {
        $this->company = $company;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return ScheduleMetaCreator
     */
    public function setEmail(?string $email): ScheduleMetaCreator
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return ScheduleMetaCreator
     */
    public function setPhone(?string $phone): ScheduleMetaCreator
    {
        $this->phone = $phone;
        return $this;
    }
}