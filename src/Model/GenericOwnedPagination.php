<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

use AllInData\Micro\Core\Helper\MethodUtil;

/**
 * Class GenericOwnedPagination
 * @package AllInData\Micro\Core\Model
 */
class GenericOwnedPagination extends GenericPagination
{
    /**
     * @var AbstractOwnedCollection
     */
    private $collection;

    /**
     * GenericOwnedPagination constructor.
     * @param AbstractOwnedCollection $collection
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
        AbstractOwnedCollection $collection,
        PaginationFilterFactory $paginationFilterFactory,
        PaginationSorterFactory $paginationSorterFactory,
        int $defaultShowPerPage = self::DEFAULT_SHOW_PER_PAGE,
        int $defaultPage = self::DEFAULT_PAGE,
        string $pageAttributeName = self::DEFAULT_PAGE_ATTRIBUTE_NAME,
        string $showPerPageAttributeName = self::DEFAULT_SHOW_PER_PAGE_ATTRIBUTE_NAME,
        string $filtersAttributeName = self::DEFAULT_FILTERS_ATTRIBUTE_NAME,
        string $sortsAttributeName = self::DEFAULT_SORTERS_ATTRIBUTE_NAME
    ) {
        parent::__construct($collection, $paginationFilterFactory, $paginationSorterFactory, $defaultShowPerPage,
            $defaultPage, $pageAttributeName, $showPerPageAttributeName, $filtersAttributeName, $sortsAttributeName);
        $this->collection = $collection;
    }

    /**
     * @param array $queryArgs
     * @return AbstractModel[]
     */
    public function loadBypassOwnership(array $queryArgs = []): array
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

        return $this->collection->loadBypassOwnership(
            $limit,
            $offset,
            $queryArgs
        );
    }

    /**
     * @param array $queryArgs
     * @return array
     */
    protected function applyQueryArguments(array $queryArgs): array
    {
        // apply current scope user id as a filter
        $queryArgs = array_merge($queryArgs, [
            'author__in' => $this->getCurrentScopeUserIdSet()
        ]);

        return $queryArgs;
    }

    /**
     * @return int[]
     */
    protected function getCurrentScopeUserIdSet(): array
    {
        return apply_filters('micro_core_query_current_scope_user_id', [get_current_user_id()]);
    }
}
