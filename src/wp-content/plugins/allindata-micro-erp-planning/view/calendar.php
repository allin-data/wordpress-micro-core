<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Planning\Block\Calendar $block */
?>
<h2>Calendar Template</h2>

<div id="calendar" style="height: 800px;"></div>

<script>
    jQuery(document).ready(function ($) {
        let Calendar = tui.Calendar;
        let lastClickSchedule;

        let calendar = new Calendar('#calendar', {
            title: 'Demo Schedule Calendar',
            defaultView: 'month',
            taskView: 'milestone',    // Can be also ['milestone', 'task']
            scheduleView: 'allday',  // Can be also ['allday', 'time']
            isReadOnly: false,
            disableClick: false,
            disableDblClick: false,
            useCreationPopup: true,
            useDetailPopup: true,
            template: {
                milestone: function (schedule) {
                    return '<span style="color:red;"><i class="fa fa-flag"></i> ' + schedule.title + '</span>';
                },
                milestoneTitle: function () {
                    return 'Milestone';
                },
                task: function (schedule) {
                    return '&nbsp;&nbsp;#' + schedule.title;
                },
                taskTitle: function () {
                    return '<label><input type="checkbox" />Task</label>';
                },
                allday: function (schedule) {
                    return schedule.title + ' <i class="fa fa-refresh"></i>';
                },
                alldayTitle: function () {
                    return 'All Day';
                },
                time: function (schedule) {
                    return schedule.title + ' <i class="fa fa-refresh"></i>' + schedule.start;
                },
                monthMoreTitleDate: function (date) {
                    date = new Date(date);
                    return tui.util.formatDate('MM-DD', date) + '(' + daynames[date.getDay()] + ')';
                },
                monthMoreClose: function () {
                    return '<i class="fa fa-close"></i>';
                },
                monthGridHeader: function (model) {
                    var date = new Date(model.date);
                    var template = '<span class="tui-full-calendar-weekday-grid-date">' + date.getDate() + '</span>';
                    var today = model.isToday ? 'TDY' : '';
                    if (today) {
                        template += '<span class="tui-full-calendar-weekday-grid-date-decorator">' + today + '</span>';
                    }
                    //if (tempHolidays[date.getDate()]) {
                    //    template += '<span class="tui-full-calendar-weekday-grid-date-title">' + tempHolidays[date.getDate()] + '</span>';
                    //}
                    return template;
                },
                monthGridHeaderExceed: function (hiddenSchedules) {
                    return '<span class="calendar-more-schedules">+' + hiddenSchedules + '</span>';
                },

                monthGridFooter: function () {
                    return '<div class="calendar-new-schedule-button">New Schedule</div>';
                },

                monthGridFooterExceed: function (hiddenSchedules) {
                    return '<span class="calendar-footer-more-schedules">+ See ' + hiddenSchedules + ' more events</span>';
                },
                weekDayname: function (dayname) {
                    return '<span class="calendar-week-dayname-name">' + dayname.dayName + '</span><br><span class="calendar-week-dayname-date">' + dayname.date + '</span>';
                },
                monthDayname: function (dayname) {
                    return '<span class="calendar-week-dayname-name">' + dayname.label + '</span>';
                },
                timegridDisplayPrimaryTime: function (time) {
                    var meridiem = time.hour < 12 ? 'am' : 'pm';

                    return time.hour + ' ' + meridiem;
                },
                timegridDisplayTime: function (time) {
                    return time.hour + ':' + time.minutes;
                },
                goingDuration: function (model) {
                    var goingDuration = model.goingDuration;
                    var hour = parseInt(goingDuration / SIXTY_MINUTES, 10);
                    var minutes = goingDuration % SIXTY_MINUTES;

                    return 'GoingTime ' + hour + ':' + minutes;
                },
                comingDuration: function (model) {
                    var goingDuration = model.goingDuration;
                    var hour = parseInt(goingDuration / SIXTY_MINUTES, 10);
                    var minutes = goingDuration % SIXTY_MINUTES;

                    return 'ComingTime ' + hour + ':' + minutes;
                },
                popupDetailRepeat: function (model) {
                    return model.recurrenceRule;
                },
                popupDetailBody: function (model) {
                    return model.body;
                }
            },
            month: {
                daynames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                startDayOfWeek: 1,
                narrowWeekend: true
            },
            week: {
                daynames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                startDayOfWeek: 1,
                narrowWeekend: true
            }
        });

        calendar.createSchedules(<?= json_encode($block->getSchedules()); ?>);

        calendar.on({
            'clickSchedule': function (e) {
                console.log('clickSchedule', e);
                var schedule = event.schedule;

                // focus the schedule
                if (lastClickSchedule) {
                    calendar.updateSchedule(lastClickSchedule.id, lastClickSchedule.calendarId, {
                        isFocused: false
                    });
                }
                calendar.updateSchedule(schedule.id, schedule.calendarId, {
                    isFocused: true
                });

                lastClickSchedule = schedule;

                // open detail view
            },
            'clickMore': function(e) {
                console.log('clickMore', e.date, e.target);
            },
            'beforeCreateSchedule': function (e) {
                console.log('beforeCreateSchedule', e);
                // open a creation popup

                // If you dont' want to show any popup, just use `e.guide.clearGuideElement()`

                // then close guide element(blue box from dragging or clicking days)
                e.guide.clearGuideElement();
            },
            'beforeUpdateSchedule': function (e) {
                console.log('beforeUpdateSchedule', e);
                e.schedule.start = e.start;
                e.schedule.end = e.end;
                cal.updateSchedule(e.schedule.id, e.schedule.calendarId, e.schedule);
            },
            'beforeDeleteSchedule': function (e) {
                console.log('beforeDeleteSchedule', e);
                cal.deleteSchedule(e.schedule.id, e.schedule.calendarId);
            }
        });


        // // daily view
        // calendar.changeView('day', true);
        //
        // // weekly view
        // calendar.changeView('week', true);
        //
        // // monthly view with 5 weeks or 6 weeks based on the month
        // calendar.setOptions({month: {isAlways6Week: false}}, true);
        // calendar.changeView('month', true);
        //
        // // monthly view(default 6 weeks view)
        // calendar.setOptions({month: {visibleWeeksCount: 6}}, true); // or null
        // calendar.changeView('month', true);
        //
        // // 2 weeks monthly view
        // calendar.setOptions({month: {visibleWeeksCount: 2}}, true);
        // calendar.changeView('month', true);
        //
        // // 3 weeks monthly view
        // calendar.setOptions({month: {visibleWeeksCount: 3}}, true);
        // calendar.changeView('month', true);
        //
        // // narrow weekend
        // calendar.setOptions({month: {narrowWeekend: true}}, true);
        // calendar.setOptions({week: {narrowWeekend: true}}, true);
        // calendar.changeView(calendar.getViewName(), true);
        //
        // // change start day of week(from monday)
        // calendar.setOptions({week: {startDayOfWeek: 1}}, true);
        // calendar.setOptions({month: {startDayOfWeek: 1}}, true);
        // calendar.changeView(calendar.getViewName(), true);
        //
        // // work week
        // calendar.setOptions({week: {workweek: true}}, true);
        // calendar.setOptions({month: {workweek: true}}, true);
        // calendar.changeView(calendar.getViewName(), true);
    });
</script>