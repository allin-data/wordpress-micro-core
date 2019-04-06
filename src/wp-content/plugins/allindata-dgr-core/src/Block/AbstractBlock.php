<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Dgr\Core\Block;

use AllInData\Dgr\Core\Controller\PluginControllerInterface;

/**
 * Class AbstractBlock
 * @package AllInData\Dgr\Core\Block
 */
abstract class AbstractBlock
{
    /**
     * @param bool $isAsync
     * @return string
     */
    public function getFormUrl($isAsync = false): string
    {
        $target = $isAsync ? 'admin-ajax.php' : 'admin-post.php';
        return esc_url(admin_url($target));
    }

    /**
     * @param string $slug
     * @return bool|string
     */
    public function getFormNonce(string $slug)
    {
        return wp_create_nonce($slug);
    }

    /**
     * @return string
     */
    public function getFormRedirectKey()
    {
        return PluginControllerInterface::REDIRECTION_KEY;
    }

    /**
     * @return string
     */
    public function getFormRedirectUrl()
    {
        global $wp;
        return home_url(add_query_arg([], $wp->request));
    }
}