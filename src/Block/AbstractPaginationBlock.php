<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Block;

use AllInData\Micro\Core\Init;
use AllInData\Micro\Core\Model\PaginationInterface;

/**
 * Class AbstractPaginationBlock
 * @package AllInData\Micro\Core\Block
 */
abstract class AbstractPaginationBlock extends AbstractBlock
{
    const PAGINATION_TEMPLATE_PATH = 'pagination';

    /**
     * @var PaginationInterface
     */
    private $pagination;
    /**
     * @var string
     */
    private $paginationTemplatePath;

    /**
     * AbstractPaginationBlock constructor.
     * @param PaginationInterface $pagination
     * @param string $paginationTemplatePath
     */
    public function __construct(
        PaginationInterface $pagination,
        string $paginationTemplatePath = Init::TEMPLATE_DIR
    ) {
        $this->pagination = $pagination;
        $this->paginationTemplatePath = $paginationTemplatePath;
    }

    /**
     * @return PaginationInterface
     */
    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }

    /**
     * @param bool $showSummary
     * @param bool $reverseLayout
     */
    public function renderPaginationBlock($showSummary = true, $reverseLayout = false)
    {
        $this->getTemplate(
            static::PAGINATION_TEMPLATE_PATH,
            [
                'block' => $this,
                'reverseLayout' => $reverseLayout,
                'showSummary' => $showSummary,
            ]
        );
    }

    /**
     * @return string
     */
    public function getPaginationTemplatePath(): string
    {
        return $this->paginationTemplatePath;
    }

    /**
     * @param string $templateName
     * @param array $args
     */
    protected function getTemplate($templateName, $args = [])
    {
        global $wp_query;
        if (is_array($args) && isset($args)) {
            $wp_query->query_vars = array_merge($wp_query->query_vars, $args);
        }

        $templateFile = $this->loadTemplate($templateName);
        if (!file_exists($templateFile)) {
            throw new \RuntimeException(sprintf(__('Template "%s" could not be found.'), $templateName));
        }
        load_template($templateFile, false);
    }

    /**
     * @param string $templateName
     * @return mixed|void
     */
    private function loadTemplate($templateName)
    {
        $template = $this->getPaginationTemplatePath() . $templateName . '.php';
        return apply_filters('micro_core_locate_template', $template, $templateName, $this->getPaginationTemplatePath());
    }
}