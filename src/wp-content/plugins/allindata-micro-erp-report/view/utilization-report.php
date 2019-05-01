<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Report\Block\EmployeeUtilizationReport $block */

?>
<div id="report_<?= $block->getReportId() ?>">
    <div id="report_<?= $block->getReportId() ?>_chart"></div>
</div>
<script>
    jQuery(document).ready(function ($) {
        $('#report_<?= $block->getReportId() ?>_chart').microErpReportUtilization({
            label: '<?= $block->getUtilizationLabelByScope() ?>',
            data: <?= $block->getUtilizationValueByScope() ?>
        });
    });
</script>