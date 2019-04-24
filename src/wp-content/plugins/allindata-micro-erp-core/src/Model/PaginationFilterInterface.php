<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Model;

/**
 * Interface PaginationFilterInterface
 * @package AllInData\MicroErp\Core\Model
 */
interface PaginationFilterInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getValue(): ?string;
}
