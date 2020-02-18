<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Widget;

/**
 * Interface ElementorWidgetInterface
 * @package AllInData\Micro\Core\Model
 */
interface ElementorWidgetInterface
{
    /**
     * Init widget
     */
    public function initialize();

    /**
     * Register current widget instance
     */
    public function registerWidget();

    /**
     * @return string
     */
    public function get_name();

    /**
     * @return string
     */
    public function get_title();

    /**
     * @return string
     */
    public function get_icon();

    /**
     * @return string[]
     */
    public function get_categories();
}
