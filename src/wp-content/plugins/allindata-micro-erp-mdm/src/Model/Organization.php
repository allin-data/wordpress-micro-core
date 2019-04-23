<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model;

use AllInData\MicroErp\Core\Model\AbstractPostModel;

/**
 * Class Organization
 * @package AllInData\MicroErp\Mdm\Model
 */
class Organization extends AbstractPostModel
{
    /**
     * @var string|null
     */
    private $companyName;
    /**
     * @var string|null
     */
    private $companyCeoSalutation;
    /**
     * @var string|null
     */
    private $companyCeoFirstName;
    /**
     * @var string|null
     */
    private $companyCeoLastName;
    /**
     * @var string|null
     */
    private $streetName;
    /**
     * @var string|null
     */
    private $streetNumber;
    /**
     * @var string|null
     */
    private $zip;
    /**
     * @var string|null
     */
    private $city;
    /**
     * @var string|null
     */
    private $country;
    /**
     * @var string|null
     */
    private $registrationId;
    /**
     * @var string|null
     */
    private $registrationLocation;

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @param string|null $companyName
     * @return Organization
     */
    public function setCompanyName(?string $companyName): Organization
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyCeoSalutation(): ?string
    {
        return $this->companyCeoSalutation;
    }

    /**
     * @param string|null $companyCeoSalutation
     * @return Organization
     */
    public function setCompanyCeoSalutation(?string $companyCeoSalutation): Organization
    {
        $this->companyCeoSalutation = $companyCeoSalutation;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyCeoFirstName(): ?string
    {
        return $this->companyCeoFirstName;
    }

    /**
     * @param string|null $companyCeoFirstName
     * @return Organization
     */
    public function setCompanyCeoFirstName(?string $companyCeoFirstName): Organization
    {
        $this->companyCeoFirstName = $companyCeoFirstName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyCeoLastName(): ?string
    {
        return $this->companyCeoLastName;
    }

    /**
     * @param string|null $companyCeoLastName
     * @return Organization
     */
    public function setCompanyCeoLastName(?string $companyCeoLastName): Organization
    {
        $this->companyCeoLastName = $companyCeoLastName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    /**
     * @param string|null $streetName
     * @return Organization
     */
    public function setStreetName(?string $streetName): Organization
    {
        $this->streetName = $streetName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    /**
     * @param string|null $streetNumber
     * @return Organization
     */
    public function setStreetNumber(?string $streetNumber): Organization
    {
        $this->streetNumber = $streetNumber;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @param string|null $zip
     * @return Organization
     */
    public function setZip(?string $zip): Organization
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return Organization
     */
    public function setCity(?string $city): Organization
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return Organization
     */
    public function setCountry(?string $country): Organization
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationId(): ?string
    {
        return $this->registrationId;
    }

    /**
     * @param string|null $registrationId
     * @return Organization
     */
    public function setRegistrationId(?string $registrationId): Organization
    {
        $this->registrationId = $registrationId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationLocation(): ?string
    {
        return $this->registrationLocation;
    }

    /**
     * @param string|null $registrationLocation
     * @return Organization
     */
    public function setRegistrationLocation(?string $registrationLocation): Organization
    {
        $this->registrationLocation = $registrationLocation;
        return $this;
    }
}