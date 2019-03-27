<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\ShortCode;

/**
 * Interface PluginShortCodeInterface
 * @package AllInData\Dgr\Core\ShortCode
 */
interface PluginShortCodeInterface
{
    /**
     * Init plugin short code
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