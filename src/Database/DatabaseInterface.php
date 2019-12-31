<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Database;

/**
 * Interface DatabaseInterface
 * @package AllInData\Micro\Core\Database
 */
interface DatabaseInterface
{
    /**
     * @return mixed
     */
    public function getInstance();
}
