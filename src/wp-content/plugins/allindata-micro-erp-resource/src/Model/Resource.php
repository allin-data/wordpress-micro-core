<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Resource\Model;

use AllInData\MicroErp\Core\Model\AbstractPostModel;

/**
 * Class Resource
 * @package AllInData\MicroErp\Resource\Model
 */
class Resource extends AbstractPostModel
{
    /**
     * @var int|null
     */
    private $typeId;
    /**
     * @var string|null
     */
    private $name;

    /**
     * @return int|null
     */
    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    /**
     * @param int|null $typeId
     * @return Resource
     */
    public function setTypeId(?int $typeId): Resource
    {
        $this->typeId = $typeId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Resource
     */
    public function setName(?string $name): Resource
    {
        $this->name = $name;
        return $this;
    }
}