<?php

declare(strict_types=1);

/*
Copyright (C) 2019 All.In Data GmbH
*/

namespace AllInData\Micro\Core\Model;

/**
 * Interface PaginationInterface
 * @package AllInData\Micro\Core\Model
 */
interface PaginationInterface
{
    /**
     * @return AbstractCollection
     */
    public function getCollection(): AbstractCollection;

    /**
     * @param array $queryArgs
     * @return AbstractModel[]
     */
    public function load(array $queryArgs = []): array;

    /**
     * @return int
     */
    public function getTotalCount(): int;

    /**
     * @return int
     */
    public function getCurrentPage(): int;

    /**
     * @return int
     */
    public function getFirstPage(): int;

    /**
     * @return int
     */
    public function getLastPage(): int;

    /**
     * @return int
     */
    public function getPageCount(): int;

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
    ): string;

    /**
     * @return int
     */
    public function getCurrentShowPerPage(): int;

    /**
     * @return PaginationFilterInterface[]
     */
    public function getCurrentFilters(): array;

    /**
     * @return PaginationSorterInterface[]
     */
    public function getCurrentSorters(): array;
}
