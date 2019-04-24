<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\MicroErp\Mdm\Model\Collection;

use AllInData\MicroErp\Core\Model\AbstractModel;
use AllInData\MicroErp\Core\Model\GenericPagination;

/**
 * Class Pagination
 * @package AllInData\MicroErp\Mdm\Model
 */
class Pagination extends GenericPagination
{
    /**
     * @return AbstractModel[]
     */
    public function load(): array
    {
        $limit = $this->getCurrentShowPerPage();
        $offset = $this->getCurrentShowPerPage() * $this->getCurrentPage();

        $queryArgs = [];

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
            $orderBySet[] = 'meta_value_' . $this->decanonicalizeAttributeName($sorter->getName());
            $metaKeySet[] = $this->decanonicalizeAttributeName($sorter->getName());
            $orderSet[] = $sorter->getDirection();
        }

        $queryArgs['search'] = implode(' ', $searchValueSet);
        $queryArgs['order'] = implode(' ', $orderSet);
        $queryArgs['orderby'] = implode(' ', $orderBySet);
        $queryArgs['meta_key'] = $metaKeySet;

        return $this->getCollection()->load(
            $limit,
            $offset,
            $queryArgs
        );
    }
}
