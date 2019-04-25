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
                useDetailPopup: true,
                theme: {
                    'common.border': '<?= $block->getCommonBorder('border') ?>',
                    'common.backgroundColor': '<?= $block->getCommonStyle('backgroundColor') ?>',
                    'common.holiday.color': '<?= $block->getCommonStyle('holiday.color') ?>',
                    'common.saturday.color': '<?= $block->getCommonStyle('saturday.color') ?>',
                    'common.dayname.color': '<?= $block->getCommonStyle('dayname.color') ?>',
                    'common.today.color':  '<?= $block->getCommonStyle('today.color') ?>',

                    // creation guide style
                    'common.creationGuide.backgroundColor': '<?= $block->getCommonStyle('creationGuide.backgroundColor') ?>',
                    'common.creationGuide.border': '<?= $block->getCommonBorder('creationGuide.border') ?>',

                    // month header 'dayname'
                    'month.dayname.height': '<?= $block->getMonthStyle('dayname.height') ?>px',
                    'month.dayname.borderLeft': '<?= $block->getCommonBorder('dayname.borderLeft') ?>',
                    'month.dayname.paddingLeft': '<?= $block->getMonthStyle('dayname.paddingLeft') ?>px',
                    'month.dayname.paddingRight': '<?= $block->getMonthStyle('dayname.paddingRight') ?>px',
                    'month.dayname.backgroundColor': '<?= $block->getMonthStyle('dayname.backgroundColor') ?>',
                    'month.dayname.fontSize': '<?= $block->getMonthStyle('dayname.fontSize') ?>px',
                    'month.dayname.fontWeight': '<?= $block->getMonthStyle('dayname.fontWeight') ?>',
                    'month.dayname.textAlign': '<?= $block->getMonthStyle('dayname.textAlign') ?>',

                    // month day grid cell 'day'
                    'month.holidayExceptThisMonth.color': '<?= $block->getMonthStyle('holidayExceptThisMonth.color') ?>',
                    'month.dayExceptThisMonth.color': '<?= $block->getMonthStyle('dayExceptThisMonth.color') ?>',
                    'month.weekend.backgroundColor': '<?= $block->getMonthStyle('weekend.backgroundColor') ?>',
                    'month.day.fontSize': '<?= $block->getMonthStyle('day.fontSize') ?>px',

                    // month schedule style
                    'month.schedule.borderRadius': '<?= $block->getMonthStyle('schedule.borderRadius') ?>px',
                    'month.schedule.height': '<?= $block->getMonthStyle('schedule.height') ?>px',
                    'month.schedule.marginTop': '<?= $block->getMonthStyle('schedule.marginTop') ?>px',
                    'month.schedule.marginLeft': '<?= $block->getMonthStyle('schedule.marginLeft') ?>px',
                    'month.schedule.marginRight': '<?= $block->getMonthStyle('schedule.marginRight') ?>px',

                    // month more view
                    'month.moreView.border': '<?= $block->getMonthBorder('moreView.border') ?>',
                    'month.moreView.boxShadow': '<?= $block->getMonthBoxShadow('moreView.boxShadow') ?>',
                    'month.moreView.backgroundColor': '<?= $block->getMonthStyle('moreView.backgroundColor') ?>',
                    'month.moreView.paddingBottom': '<?= $block->getMonthStyle('moreView.paddingBottom') ?>px',
                    'month.moreViewTitle.height': '<?= $block->getMonthStyle('moreViewTitle.height') ?>px',
                    'month.moreViewTitle.marginBottom': '<?= $block->getMonthStyle('moreViewTitle.marginBottom') ?>px',
                    'month.moreViewTitle.backgroundColor': '<?= $block->getMonthStyle('moreViewTitle.backgroundColor') ?>',
                    'month.moreViewTitle.borderBottom': '<?= $block->getMonthBorder('moreViewTitle.borderBottom') ?>',
                    'month.moreViewTitle.padding': '<?= $block->getMonthPadding('moreViewTitle.padding') ?>',
                    'month.moreViewList.padding': '<?= $block->getMonthPadding('moreViewList.padding') ?>',

                    // week header 'dayname'
                    'week.dayname.height': '<?= $block->getWeekStyle('dayname.height') ?>px',
                    'week.dayname.borderTop': '<?= $block->getWeekBorder('dayname.borderTop') ?>',
                    'week.dayname.borderBottom': '<?= $block->getWeekBorder('dayname.borderBottom') ?>',
                    'week.dayname.borderLeft': '<?= $block->getWeekBorder('dayname.borderLeft') ?>',
                    'week.dayname.paddingLeft': '<?= $block->getWeekStyle('dayname.paddingLeft') ?>px',
                    'week.dayname.backgroundColor': '<?= $block->getWeekStyle('dayname.backgroundColor\'') ?>',
                    'week.dayname.textAlign': '<?= $block->getWeekStyle('dayname.textAlign') ?>',
                    'week.today.color': '<?= $block->getWeekStyle('today.color') ?>',
                    'week.pastDay.color': '<?= $block->getWeekStyle('pastDay.color') ?>',

                    // week vertical panel 'vpanel'
                    'week.vpanelSplitter.border': '<?= $block->getWeekBorder('vpanelSplitter.border') ?>',
                    'week.vpanelSplitter.height': '<?= $block->getWeekStyle('vpanelSplitter.height') ?>px',

                    // week daygrid 'daygrid'
                    'week.daygrid.borderRight': '<?= $block->getWeekBorder('daygrid.borderRight') ?>',
                    'week.daygrid.backgroundColor': '<?= $block->getWeekStyle('daygrid.backgroundColor') ?>',

                    'week.daygridLeft.width': '<?= $block->getWeekStyle('daygridLeft.width') ?>px',
                    'week.daygridLeft.backgroundColor': '<?= $block->getWeekStyle('daygridLeft.backgroundColor') ?>',
                    'week.daygridLeft.paddingRight': '<?= $block->getWeekStyle('daygridLeft.paddingRight') ?>px',
                    'week.daygridLeft.borderRight': '<?= $block->getWeekBorder('daygridLeft.borderRight') ?>',

                    'week.today.backgroundColor': '<?= $block->getWeekStyle('today.backgroundColor') ?>',
                    'week.weekend.backgroundColor': '<?= $block->getWeekStyle('weekend.backgroundColor') ?>',

                    // week timegrid 'timegrid'
                    'week.timegridLeft.width': '<?= $block->getWeekStyle('timegridLeft.width') ?>px',
                    'week.timegridLeft.backgroundColor': '<?= $block->getWeekStyle('timegridLeft.backgroundColor') ?>',
                    'week.timegridLeft.borderRight': '<?= $block->getWeekBorder('timegridLeft.borderRight') ?>',
                    'week.timegridLeft.fontSize': '<?= $block->getWeekStyle('timegridLeft.fontSize') ?>px',
                    'week.timegridLeftTimezoneLabel.height': '<?= $block->getWeekStyle('timegridLeftTimezoneLabel.height') ?>px',
                    'week.timegridLeftAdditionalTimezone.backgroundColor': '<?= $block->getWeekStyle('timegridLeftAdditionalTimezone.backgroundColor') ?>',

                    'week.timegridOneHour.height': '<?= $block->getWeekStyle('timegridOneHour.height') ?>px',
                    'week.timegridHalfHour.height': '<?= $block->getWeekStyle('timegridHalfHour.height') ?>px',
                    'week.timegridHalfHour.borderBottom': '<?= $block->getWeekBorder('timegridHalfHour.borderBottom') ?>',
                    'week.timegridHorizontalLine.borderBottom': '<?= $block->getWeekBorder('timegridHorizontalLine.borderBottom') ?>',

                    'week.timegrid.paddingRight': '<?= $block->getWeekStyle('timegrid.paddingRight') ?>px',
                    'week.timegrid.borderRight': '<?= $block->getWeekBorder('timegrid.borderRight') ?>',
                    'week.timegridSchedule.borderRadius': '<?= $block->getWeekStyle('timegridSchedule.borderRadius') ?>px',
                    'week.timegridSchedule.paddingLeft': '<?= $block->getWeekStyle('timegridSchedule.paddingLeft') ?>px',

                    'week.currentTime.color': '<?= $block->getWeekStyle('currentTime.color') ?>',
                    'week.currentTime.fontSize': '<?= $block->getWeekStyle('currentTime.fontSize') ?>px',
                    'week.currentTime.fontWeight': '<?= $block->getWeekStyle('currentTime.fontWeight') ?>',

                    'week.pastTime.color': '<?= $block->getWeekStyle('pastTime.color') ?>',
                    'week.pastTime.fontWeight': '<?= $block->getWeekStyle('pastTime.fontWeight') ?>',

                    'week.futureTime.color': '<?= $block->getWeekStyle('futureTime.color') ?>',
                    'week.futureTime.fontWeight': '<?= $block->getWeekStyle('futureTime.fontWeight') ?>',

                    'week.currentTimeLinePast.border': '<?= $block->getWeekBorder('currentTimeLinePast.border') ?>',
                    'week.currentTimeLineBullet.backgroundColor': '<?= $block->getWeekStyle('currentTimeLineBullet.backgroundColor') ?>',
                    'week.currentTimeLineToday.border': '<?= $block->getWeekBorder('currentTimeLineToday.border') ?>',
                    'week.currentTimeLineFuture.border': '<?= $block->getWeekBorder('currentTimeLineFuture.border') ?>',

                    // week creation guide style
                    'week.creationGuide.color': '<?= $block->getWeekStyle('creationGuide.color') ?>',
                    'week.creationGuide.fontSize': '<?= $block->getWeekStyle('creationGuide.fontSize') ?>px',
                    'week.creationGuide.fontWeight': '<?= $block->getWeekStyle('creationGuide.fontWeight') ?>',

                    // week daygrid schedule style
                    'week.dayGridSchedule.borderRadius': '<?= $block->getWeekStyle('dayGridSchedule.borderRadius') ?>px',
                    'week.dayGridSchedule.height': '<?= $block->getWeekStyle('dayGridSchedule.height') ?>px',
                    'week.dayGridSchedule.marginTop': '<?= $block->getWeekStyle('dayGridSchedule.marginTop') ?>px',
                    'week.dayGridSchedule.marginLeft': '<?= $block->getWeekStyle('dayGridSchedule.marginLeft') ?>px',
                    'week.dayGridSchedule.marginRight': '<?= $block->getWeekStyle('dayGridSchedule.marginRight') ?>px'
                }
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