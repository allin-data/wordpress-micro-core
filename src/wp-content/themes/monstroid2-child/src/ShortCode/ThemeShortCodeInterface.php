<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Theme\ShortCode;

/**
 * Interface ThemeShortCodeInterface
 * @package AllInData\MicroErp\Theme\ShortCode
 */
interface ThemeShortCodeInterface
{
    /**
     * Init theme short code
     */
    public function init();

    /**
     * Applies short code
     * @param array $attributes
     * @param string $content
     * @param string $name
     * @return mixed
     */
    public function addShortCode($attributes, $content, $name);
}