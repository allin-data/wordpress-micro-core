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

<nav id="calendar-menu">
    <div class="justify-content-center">
        <button type="button" class="btn btn-default btn-sm move-today" data-action="move-today">
            <?php _e('Today', AID_MICRO_ERP_PLANNING_TEXTDOMAIN); ?>
        </button>
        <button type="button" class="btn btn-default btn-sm move-prev" data-action="move-prev">
            <i class="fas fa-angle-double-left" data-action="move-prev"></i>
        </button>
        <button type="button" class="btn btn-default btn-sm move-next" data-action="move-next">
            <i class="fas fa-angle-double-right" data-action="move-next"></i>
        </button>
        <span class="calendar-render-range">...</span>
    </div>
</nav>

<div id="calendar_<?= $block->getAttribute('id') ?>" class="planning-calendar"></div>

<div id="calendar_modal"><p>Foobar</p></div>

<script>
    jQuery(document).ready(function ($) {
        let calendarSelector = '#calendar_<?= $block->getAttribute('id') ?>',
            currentDate = moment();

        $(calendarSelector).microErpPlanningCalendar({
            calendarId: 1,
            target: calendarSelector,
            actionCreateSchedule: '<?= $block->getCreateScheduleActionSlug() ?>',
            actionUpdateSchedule: '<?= $block->getUpdateScheduleActionSlug() ?>',
            actionDeleteSchedule: '<?= $block->getDeleteScheduleActionSlug() ?>',
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
            initialDate: currentDate,
            renderDateSelector: '.calendar-render-range',
            calendarOptions: {
                title: '<?= $block->getTitle(); ?>',
                defaultView: '<?= $block->getAttribute('default-view'); ?>',
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

        $('.move-today').click(function () {
            $(calendarSelector).trigger('calendar-today');
        });
        $('.move-next').click(function () {
            $(calendarSelector).trigger('calendar-next');
        });
        $('.move-prev').click(function () {
            $(calendarSelector).trigger('calendar-prev');
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