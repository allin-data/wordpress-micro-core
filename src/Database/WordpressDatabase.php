<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Core\Database;

use wpdb;

/**
 * Class WordpressDatabase
 * @package AllInData\MicroErp\Core\Database
 */
class WordpressDatabase implements DatabaseInterface
{
    /**
     * @var wpdb
     */
    private $instance;

    /**
     * WordpressDatabase constructor.
     * @param wpdb $instance
     */
    public function __construct(wpdb $instance)
    {
        $this->instance = $instance;
    }

    /**
     * @return wpdb
     */
    public function getInstance()
    {
        return $this->instance;
    }
}
