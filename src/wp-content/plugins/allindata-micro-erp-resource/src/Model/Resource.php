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
}