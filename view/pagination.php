<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var bool $reverseLayout */
/** @var bool $showSummary */
/** @var \AllInData\Micro\Core\Block\AbstractPaginationBlock $block */
$pagination = $block->getPagination();
$currentPage = $pagination->getCurrentPage();
$isDisabledPrevious = $currentPage === $pagination->getFirstPage();
$isDisabledNext = $currentPage === $pagination->getLastPage();
?>
<?php if ($pagination->getPageCount() > 1): ?>
    <?php if ($reverseLayout): /* Top Layout */ ?>
        <nav aria-label="<?php _e('Pagination', \AllInData\Micro\Core\Init::SLUG) ?>">
            <ul class="pagination justify-content-center">

                <li class="page-item <?php if ($isDisabledPrevious): ?>disabled<?php endif; ?>">
                    <a class="page-link"
                       href="<?= $pagination->getPageUrl($currentPage - 1); ?>"
                        <?php if ($isDisabledPrevious): ?> tabindex="-1" aria-disabled="true"<?php endif; ?>
                       aria-label="<?php _e('Previous', \AllInData\Micro\Core\Init::SLUG) ?>">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($page = $pagination->getFirstPage(); $page <= $pagination->getLastPage(); $page++): ?>
                    <?php if ($currentPage == $page): /* Active Page */ ?>
                        <li class="page-item active" aria-current="page">
                <span class="page-link">
                    <?= $page + 1 ?>
                    <span class="sr-only">(current)</span>
                </span>
                        </li>
                    <?php else: /* Page Navigation Item */ ?>
                        <li class="page-item" aria-current="page">
                            <a class="page-link" href="<?= $pagination->getPageUrl($page); ?>">
                                <?= $page + 1 ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <li class="page-item <?php if ($isDisabledNext): ?>disabled<?php endif; ?>">
                    <a class="page-link"
                       href="<?= $pagination->getPageUrl($currentPage + 1); ?>"
                        <?php if ($isDisabledNext): ?> tabindex="-1" aria-disabled="true"<?php endif; ?>
                       aria-label="<?php _e('Next', \AllInData\Micro\Core\Init::SLUG) ?>">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
            <div class="summary justify-content-center">
                <p><?php _e('Total items', \AllInData\Micro\Core\Init::SLUG) ?>: <?= $pagination->getTotalCount() ?></p>
            </div>
        </nav>
    <?php else: /* Bottom Layout */ ?>
        <nav aria-label="<?php _e('Pagination', \AllInData\Micro\Core\Init::SLUG) ?>">
            <div class="summary justify-content-center">
                <p><?php _e('Total items', \AllInData\Micro\Core\Init::SLUG) ?>: <?= $pagination->getTotalCount() ?></p>
            </div>
            <ul class="pagination justify-content-center">

                <li class="page-item <?php if ($isDisabledPrevious): ?>disabled<?php endif; ?>">
                    <a class="page-link"
                       href="<?= $pagination->getPageUrl($currentPage - 1); ?>"
                        <?php if ($isDisabledPrevious): ?> tabindex="-1" aria-disabled="true"<?php endif; ?>
                       aria-label="<?php _e('Previous', \AllInData\Micro\Core\Init::SLUG) ?>">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($page = $pagination->getFirstPage(); $page <= $pagination->getLastPage(); $page++): ?>
                    <?php if ($currentPage == $page): /* Active Page */ ?>
                        <li class="page-item active" aria-current="page">
                <span class="page-link">
                    <?= $page + 1 ?>
                    <span class="sr-only">(current)</span>
                </span>
                        </li>
                    <?php else: /* Page Navigation Item */ ?>
                        <li class="page-item" aria-current="page">
                            <a class="page-link" href="<?= $pagination->getPageUrl($page); ?>">
                                <?= $page + 1 ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <li class="page-item <?php if ($isDisabledNext): ?>disabled<?php endif; ?>">
                    <a class="page-link"
                       href="<?= $pagination->getPageUrl($currentPage + 1); ?>"
                        <?php if ($isDisabledNext): ?> tabindex="-1" aria-disabled="true"<?php endif; ?>
                       aria-label="<?php _e('Next', \AllInData\Micro\Core\Init::SLUG) ?>">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif;