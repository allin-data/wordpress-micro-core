<?php

/*
Copyright (C) 2019 All.In Data GmbH
*/
?>
<h2>Calendar Template</h2>

<div id="calendar" style="height: 800px;"></div>

<script>
    jQuery(document).ready(function () {
        jQuery('#calendar').tuiCalendar({
            defaultView: 'month',
            taskView: true,
            template: {
                monthGridHeader: function (model) {
                    var date = new Date(model.date);
                    var template = '<span class="tui-full-calendar-weekday-grid-date">' + date.getDate() + '</span>';
                    return template;
                }
            }
        });
    });
</script>