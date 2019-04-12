<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Planning\Block\Calendar $block */
?>
<h2>Calendar Template</h2>

<div class="row planning-calendar-controls">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group mr-2" role="group">
            <div id="view-month" class="btn btn-primary">Monatsplaner</div>
            <div id="view-week" class="btn btn-secondary">Wochenplaner</div>
            <div id="view-day" class="btn btn-secondary">Tagesplaner</div>
        </div>
    </div>
</div>

<div id="calendar" class="planning-calendar"></div>

<script>
    jQuery(document).ready(function ($) {

        $('#calendar').microErpPlanningCalendar({
            target: '#calendar',
            labels: {
                'Milestone': '<?php _e('Milestone', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'Task': '<?php _e('Task', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'All Day': '<?php _e('All Day', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'New Schedule': '<?php _e('New Schedule', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'See %1$s more events': '<?php _e('See %1$s more events', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'GoingTime': '<?php _e('GoingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'ComingTime': '<?php _e('ComingTime', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'Sunday': '<?php _e('Sunday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'Monday': '<?php _e('Monday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'Tuesday': '<?php _e('Tuesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'Wednesday': '<?php _e('Wednesday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'Thursday': '<?php _e('Thursday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'Friday': '<?php _e('Friday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>',
                'Saturday': '<?php _e('Saturday', AID_MICRO_ERP_PLANNING_TEXTDOMAIN) ?>'
            },
            calendarOptions: {
                title: 'Demo Schedule Calendar',
                defaultView: 'month',
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
            $('#calendar')
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
            $('#calendar')
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
            $('#calendar')
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
    });
</script>