<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Planning\Block\Calendar $block */
?>
<h2><?= $block->getTitle(); ?></h2>

<div class="row planning-calendar-controls">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group mr-2" role="group">
            <div id="view-month" class="btn btn-primary"><?= $block->getAttribute('label-schedule_monthly') ?></div>
            <div id="view-week" class="btn btn-secondary"><?= $block->getAttribute('label-schedule_weekly') ?></div>
            <div id="view-day" class="btn btn-secondary"><?= $block->getAttribute('label-schedule_daily') ?></div>
        </div>
    </div>
</div>

<div id="calendar_<?= $block->getAttribute('id') ?>" class="planning-calendar"></div>

<div id="calendar_modal"><p>Foobar</p></div>

<script>
    jQuery(document).ready(function ($) {
        let calendarSelector = '#calendar_<?= $block->getAttribute('id') ?>';
        $(calendarSelector).microErpPlanningCalendar({
            calendarId: 1,
            target: calendarSelector,
            actionCreateSchedule: '<?= $block->getCreateScheduleActionSlug() ?>',
            modalSelector: '#calendar_modal',
            labels: {
                'Milestone': '<?= $block->getAttribute('label-milestone'); ?>',
                'Task': '<?= $block->getAttribute('label-task'); ?>',
                'All Day': '<?= $block->getAttribute('label-all_day'); ?>',
                'New Schedule': '<?= $block->getAttribute('label-new_schedule'); ?>',
                'See %1$s more events': '<?= $block->getAttribute('label-more_events'); ?>',
                'GoingTime': '<?= $block->getAttribute('label-going_time'); ?>',
                'ComingTime': '<?= $block->getAttribute('label-coming_time'); ?>',
                'Sunday': '<?= $block->getAttribute('label-sunday'); ?>',
                'Monday': '<?= $block->getAttribute('label-monday'); ?>',
                'Tuesday': '<?= $block->getAttribute('label-tuesday'); ?>',
                'Wednesday': '<?= $block->getAttribute('label-wednesday'); ?>',
                'Thursday': '<?= $block->getAttribute('label-thursday'); ?>',
                'Friday': '<?= $block->getAttribute('label-friday'); ?>',
                'Saturday': '<?= $block->getAttribute('label-saturday'); ?>'
            },
            calendarOptions: {
                title: '<?= $block->getTitle(); ?>',
                defaultView: '<?= $block->getAttribute('default-view'); ?>',
                date: '<?= date('Y-m-d\TH:i:s+09:00'); ?>',
                taskView: 'milestone',
                scheduleView: 'allday',
                isReadOnly: false,
                disableClick: false,
                disableDblClick: false,
                useCreationPopup: true,
                useDetailPopup: true
            },
            schedules: <?= json_encode($block->getSchedules()); ?>
        });

        $('#view-month').click(function () {
            let button = $(this);
            $(calendarSelector)
                .trigger('calendar-set-view', ['month', function () {
                    // after callback
                    $('.planning-calendar-controls')
                        .find('.btn')
                        .removeClass('btn-primary')
                        .addClass('btn-secondary');
                    button
                        .removeClass('btn-secondary')
                        .addClass('btn-primary');
                }]);
        });

        $('#view-week').click(function () {
            let button = $(this);
            $(calendarSelector)
                .trigger('calendar-set-view', ['week', function () {
                    // after callback
                    $('.planning-calendar-controls')
                        .find('.btn')
                        .removeClass('btn-primary')
                        .addClass('btn-secondary');
                    button
                        .removeClass('btn-secondary')
                        .addClass('btn-primary');
                }]);
        });

        $('#view-day').click(function () {
            let button = $(this);
            $(calendarSelector)
                .trigger('calendar-set-view', ['day', function () {
                    // after callback
                    $('.planning-calendar-controls')
                        .find('.btn')
                        .removeClass('btn-primary')
                        .addClass('btn-secondary');
                    button
                        .removeClass('btn-secondary')
                        .addClass('btn-primary');
                }]);
        });

        $('#view-<?= $block->getAttribute('default-view'); ?>').trigger('click');
    });
</script>