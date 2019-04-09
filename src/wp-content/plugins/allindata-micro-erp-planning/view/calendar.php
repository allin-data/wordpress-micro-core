<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/
?>
<h2>Calendar Template</h2>

<div id="calendar" style="height: 800px;"></div>

<script>
    $(document).ready(function () {
        var Calendar = tui.Calendar;
        var calendar = new Calendar('#calendar', {
            defaultView: 'month',
            taskView: true,
            template: {
                monthGridHeader: function(model) {
                    var date = new Date(model.date);
                    var template = '<span class="tui-full-calendar-weekday-grid-date">' + date.getDate() + '</span>';
                    return template;
                }
            }
        });

        $('#calendar').tuiCalendar({
            defaultView: 'month',
            taskView: true,
            template: {
                monthGridHeader: function(model) {
                    var date = new Date(model.date);
                    var template = '<span class="tui-full-calendar-weekday-grid-date">' + date.getDate() + '</span>';
                    return template;
                }
            }
        });

        calendar.createSchedules([
            {
                id: '1',
                calendarId: '1',
                title: 'my schedule',
                category: 'time',
                dueDateClass: '',
                start: '2019-04-18T22:30:00+09:00',
                end: '2019-04-19T02:30:00+09:00'
            },
            {
                id: '2',
                calendarId: '1',
                title: 'second schedule',
                category: 'time',
                dueDateClass: '',
                start: '2019-04-18T17:30:00+09:00',
                end: '2019-04-19T17:31:00+09:00',
                isReadOnly: true    // schedule is read-only
            }
        ]);
    });
</script>