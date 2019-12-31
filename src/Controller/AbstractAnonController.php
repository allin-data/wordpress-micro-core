<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Controller;

/**
 * Class AbstractAdminController
 * @package AllInData\Micro\Core\Controller
 */
abstract class AbstractAnonController extends AbstractController implements PluginControllerInterface
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        add_action('admin_post_nopriv_' . static::ACTION_SLUG, [$this, 'execute']);
        add_action('wp_ajax_nopriv_' . static::ACTION_SLUG, [$this, 'execute']);
    }

    /**
     * @inheritDoc
     */
    protected function isAllowed(): bool
    {
        return true;
    }
}