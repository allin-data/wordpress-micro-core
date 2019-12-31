<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Block;

use AllInData\Micro\Core\Controller\AbstractController;
use AllInData\Micro\Core\Model\AbstractModel;

/**
 * Class AbstractBlock
 * @package AllInData\Micro\Core\Block
 */
abstract class AbstractBlock
{
    /**
     * @var array
     */
    private $attributes = [];

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
        return AbstractController::REDIRECTION_KEY;
    }

    /**
     * @return string
     */
    public function getFormRedirectUrl()
    {
        global $wp;
        return home_url(add_query_arg([], $wp->request));
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addAttribute(string $key, $value)
    {
        $this->attributes[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param string $key
     * @param mixed|null $defaultValue
     * @return mixed|null
     */
    public function getAttribute(string $key, $defaultValue = null)
    {
        return $this->attributes[$key] ?? $defaultValue;
    }
}