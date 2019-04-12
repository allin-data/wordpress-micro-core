<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/

/** @var \AllInData\MicroErp\Planning\Block\Calendar $block */
?>
<h2>Calendar Template</h2>

<div class="planning-calendar-controls">
    <div id="view-year" class="button">Jahresplaner</div>
    <div id="view-month" class="button">Monatsplaner</div>
    <div id="view-day" class="button">Tagesplaner</div>
</div>
<div id="calendar" class="planning-calendar"></div>

<script>
    jQuery(document).ready(function ($) {

        $('#calendar').microErpPlanningCalendar({
            target: '#calenda',
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

        $('#view-year').click(function () {
            $('#calendar')
                .trigger('calendar-set-view', ['year', function () {
                    console.log('Set calendar view to year');
                }]);
        });

        $('#view-month').click(function () {
            $('#calendar')
                .trigger('calendar-set-view', ['month', function () {
                    console.log('Set calendar view to year');
                }]);
        });

        $('#view-day').click(function () {
            $('#calendar')
                .trigger('calendar-set-view', ['day', function () {
                    console.log('Set calendar view to year');
                }]);
        });

        // setTimeout(function () {
        //     $('#calendar').trigger('calendar-set-view', ['day', function () {
        //         console.log('custom calendar set view event');
        //     }]);
        //     setTimeout(function () {
        //         $('#calendar')
        //             .trigger('calendar-set-view', ['month', function () {
        //                 console.log('custom calendar set view event');
        //             }])
        //             .trigger('calendar-set-option', [{month: {visibleWeeksCount: 2}}, function () {
        //                 console.log('custom calendar set view event');
        //             }]);
        //
        //         setTimeout(function () {
        //             $('#calendar').trigger('calendar-refresh', function () {
        //                 console.log('custom calendar refresh event');
        //             });
        //         }, 5000);
        //     }, 5000);
        // }, 5000);
    });
</script>