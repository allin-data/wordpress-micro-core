<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

use AllInData\Micro\Core\Helper\MethodUtil;
use AllInData\Micro\Core\Helper\RequestUtil;

/**
 * Class AbstractPagination
 * @package AllInData\Micro\Core\Model
 */
abstract class AbstractPagination implements PaginationInterface
{
    const DEFAULT_PAGE_ATTRIBUTE_NAME = 'current-page';
    const DEFAULT_SHOW_PER_PAGE_ATTRIBUTE_NAME = 'per-page';
    const DEFAULT_FILTERS_ATTRIBUTE_NAME = 'filters';
    const DEFAULT_SORTERS_ATTRIBUTE_NAME = 'sorts';
    const DEFAULT_PAGE = 0;
    const DEFAULT_SHOW_PER_PAGE = 20;

    /**
     * @var AbstractCollection
     */
    private $collection;
    /**
     * @var PaginationFilterFactory
     */
    private $paginationFilterFactory;
    /**
     * @var PaginationSorterFactory
     */
    private $paginationSorterFactory;
    /**
     * @var int
     */
    private $defaultShowPerPage;
    /**
     * @var int
     */
    private $defaultPage;
    /**
     * @var string
     */
    private $pageAttributeName;
    /**
     * @var string
     */
    private $showPerPageAttributeName;
    /**
     * @var string
     */
    private $filtersAttributeName;
    /**
     * @var string
     */
    private $sortsAttributeName;

    /**
     * AbstractPagination constructor.
     * @param AbstractCollection $collection
     * @param PaginationFilterFactory $paginationFilterFactory
     * @param PaginationSorterFactory $paginationSorterFactory
     * @param int $defaultShowPerPage
     * @param int $defaultPage
     * @param string $pageAttributeName
     * @param string $showPerPageAttributeName
     * @param string $filtersAttributeName
     * @param string $sortsAttributeName
     */
    public function __construct(
        AbstractCollection $collection,
        PaginationFilterFactory $paginationFilterFactory,
        PaginationSorterFactory $paginationSorterFactory,
        int $defaultShowPerPage = self::DEFAULT_SHOW_PER_PAGE,
        int $defaultPage = self::DEFAULT_PAGE,
        string $pageAttributeName = self::DEFAULT_PAGE_ATTRIBUTE_NAME,
        string $showPerPageAttributeName = self::DEFAULT_SHOW_PER_PAGE_ATTRIBUTE_NAME,
        string $filtersAttributeName = self::DEFAULT_FILTERS_ATTRIBUTE_NAME,
        string $sortsAttributeName = self::DEFAULT_SORTERS_ATTRIBUTE_NAME
    ) {
        $this->collection = $collection;
        $this->paginationFilterFactory = $paginationFilterFactory;
        $this->paginationSorterFactory = $paginationSorterFactory;
        $this->pageAttributeName = $pageAttributeName;
        $this->showPerPageAttributeName = $showPerPageAttributeName;
        $this->defaultPage = $defaultPage;
        $this->defaultShowPerPage = $defaultShowPerPage;
        $this->filtersAttributeName = $filtersAttributeName;
        $this->sortsAttributeName = $sortsAttributeName;
    }

    /**
     * @return AbstractCollection
     */
    public function getCollection(): AbstractCollection
    {
        return $this->collection;
    }

    /**
     * @param array $queryArgs
     * @return AbstractModel[]
     */
    public function load(array $queryArgs = []): array
    {
        $limit = $this->getCurrentShowPerPage();
        $offset = $this->getCurrentShowPerPage() * $this->getCurrentPage();

        $searchValueSet = [];
        // filters
        // @TODO use filter 'posts_where' to improve filter search
        // @TODO see also: https://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
        foreach ($this->getCurrentFilters() as $filter) {
            $searchValueSet[$filter->getName()] = $filter->getValue();
        }

        // sorters
        // @TODO improvement required for different scalar types
        $orderSet = [];
        $orderBySet = [];
        $metaKeySet = [];
        foreach ($this->getCurrentSorters() as $sorter) {
            $orderBySet[] = 'meta_value_' . MethodUtil::decanonicalizeAttributeName($sorter->getName());
            $metaKeySet[] = MethodUtil::decanonicalizeAttributeName($sorter->getName());
            $orderSet[] = $sorter->getDirection();
        }

        $queryArgs['s'] = implode(' ', $searchValueSet);
        $queryArgs['order'] = $orderSet;
        $queryArgs['orderby'] = $orderBySet;
        $queryArgs['meta_key'] = $metaKeySet;
        $queryArgs = $this->applyQueryArguments($queryArgs);
        return $this->collection->load(
            $limit,
            $offset,
            $queryArgs
        );
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->collection->getTotalCount();
    }

    /**
     * @return int
     */
    public function getDefaultPage(): int
    {
        return $this->defaultPage;
    }

    /**
     * @return string
     */
    public function getPageAttributeName(): string
    {
        return $this->pageAttributeName;
    }

    /**
     * @return int
     */
    public function getDefaultShowPerPage(): int
    {
        return $this->defaultShowPerPage;
    }

    /**
     * @return string
     */
    public function getShowPerPageAttributeName(): string
    {
        return $this->showPerPageAttributeName;
    }

    /**
     * @return int
     */
    public function getFirstPage(): int
    {
        return 0;
    }

    /**
     * @return int
     */
    public function getPageCount(): int
    {
        return $this->getLastPage() + 1;
    }

    /**
     * @return int
     */
    public function getLastPage(): int
    {
        $total = $this->getTotalCount();
        $currentShowPerPage = $this->getCurrentShowPerPage();
        $lastPage = (int)floor($total / $currentShowPerPage) - 1;
        if (0 !== ($total % $currentShowPerPage)) {
            ++$lastPage;
        }

        return $lastPage;
    }

    /**
     * @param int|null $page If null, current page is used
     * @param PaginationFilterInterface[] $additionalFilters
     * @param PaginationSorterInterface[] $additionalSorters
     * @return string
     */
    public function getPageUrl(
        $page = null,
        array $additionalFilters = [],
        array $additionalSorters = []
    ): string {
        $queryValues = [];

        $filters = (array)RequestUtil::getParamAsArray(
            $this->filtersAttributeName,
            []
        );
        foreach ($additionalFilters as $additionalFilter) {
            /** @var GenericPaginationFilter $additionalFilter */
            $filters[] = $additionalFilter->toArray();
        }

        $sorters = (array)RequestUtil::getParamAsArray(
            $this->sortsAttributeName,
            []
        );
        foreach ($additionalSorters as $additionalSorter) {
            /** @var GenericPaginationSorter $additionalSorter */
            $sorters[] = $additionalSorter->toArray();
        }

        $queryValues[$this->pageAttributeName] = $page ?? $this->getCurrentPage();
        $queryValues[$this->showPerPageAttributeName] = $this->getCurrentShowPerPage();
        $queryValues[$this->filtersAttributeName] = $filters;
        $queryValues[$this->sortsAttributeName] = $sorters;

        return esc_url(add_query_arg($queryValues));
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        $value = (int)RequestUtil::getParam(
            $this->pageAttributeName,
            $this->defaultPage,
            FILTER_SANITIZE_NUMBER_INT
        );
        return $value;
    }

    /**
     * @return int
     */
    public function getCurrentShowPerPage(): int
    {
        $value = (int)RequestUtil::getParam(
            $this->showPerPageAttributeName,
            $this->defaultShowPerPage,
            FILTER_SANITIZE_NUMBER_INT
        );
        return $value;
    }

    /**
     * @return PaginationFilterInterface[]
     */
    public function getCurrentFilters(): array
    {
        $values = (array)RequestUtil::getParamAsArray(
            $this->filtersAttributeName,
            []
        );
        $filters = [];
        foreach ($values as $valueSet) {
            $filters[] = $this->paginationFilterFactory->create($valueSet);
        }
        return $filters;
    }

    /**
     * @return PaginationSorterInterface[]
     */
    public function getCurrentSorters(): array
    {
        $values = (array)RequestUtil::getParamAsArray(
            $this->sortsAttributeName,
            []
        );
        $sorters = [];
        foreach ($values as $valueSet) {
            $sorters[] = $this->paginationSorterFactory->create($valueSet);
        }
        return $sorters;
    }

    /**
     * @param array $queryArgs
     * @return array
     */
    protected function applyQueryArguments(array $queryArgs): array
    {
        return $queryArgs;
    }
}
