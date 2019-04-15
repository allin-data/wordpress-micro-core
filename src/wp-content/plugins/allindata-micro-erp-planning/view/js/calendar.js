(function ($) {
    $.microErpPlanningCalendar = {
        translatedLabels: {},

        lastClickedSchedule: null,

        defaults: {
            target: '#calendar',
            modalSelector: '#calendar_modal',
            actionCreateSchedule: '',
            labels: {
                'Milestone': 'Milestone',
                'Task': 'Task',
                'All Day': 'All Day',
                'New Schedule': 'New Schedule',
                'See %1$s more events': 'See %1$s more events',
                'GoingTime': 'GoingTime',
                'ComingTime': 'ComingTime',
                'Sunday': 'Sunday',
                'Monday': 'Monday',
                'Tuesday': 'Tuesday',
                'Wednesday': 'Wednesday',
                'Thursday': 'Thursday',
                'Friday': 'Friday',
                'Saturday': 'Saturday'
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
                useDetailPopup: true,
                date: '',
                template: {},
                month: {},
                week: {}
            },
            schedules: []
        },

        /**
         * Constructor
         * @param {Object} options
         */
        create: function (options) {
            let config;

            // configuration
            config = $.extend({}, this.defaults, options || {});

            // translations
            this.translatedLabels = $.extend({}, this.defaults.labels, options.labels || {});

            // templates
            config.calendarOptions.template = $.extend({}, this._getTemplates(config), config.calendarOptions.template || {});
            config.calendarOptions.month = $.extend({}, this._getMonthDefintion(config), config.calendarOptions.month || {});
            config.calendarOptions.week = $.extend({}, this._getWeekDefintion(config), config.calendarOptions.week || {});

            this._createCalendar(config, this._addHooks);
        },

        /**
         *
         * @param {string} labelKey
         * @param {array} args
         * @returns {string}
         * @private
         */
        _e: function (labelKey, args = []) {
            let translation = labelKey;
            if (labelKey in this.translatedLabels) {
                translation = this.translatedLabels[labelKey]
            }
            return vsprintf(translation, args);
        },

        /**
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _createCalendar: function (config, callback = function () {
        }) {
            let Calendar = tui.Calendar,
                calendarInstance = new Calendar(config.target, config.calendarOptions);

            callback.call(this, calendarInstance, config);
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _updateCalendar: function (calendar, config, callback = function () {
        }) {
            calendar.changeView(calendar.getViewName(), true);
            callback.call(this, calendar, config);
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} option
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _setCalendarOption: function (calendar, option, config, callback = function () {
        }) {
            calendar.setOptions(option, true);
            callback.call(this, calendar, config);
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {string} view
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _setCalendarView: function (calendar, view, config, callback = function () {
        }) {
            calendar.changeView(view, true);
            callback.call(this, calendar, config);
        },

        /**
         * @param {Object} config
         * @return {Object}
         * @private
         */
        _getTemplates: function (config) {
            let me = this;
            return {
                milestone: function (schedule) {
                    return '<span style="color:red;"><i class="fa fa-flag"></i> ' + schedule.title + '</span>';
                },
                milestoneTitle: function () {
                    return me._e('Milestone');
                },
                task: function (schedule) {
                    return '&nbsp;&nbsp;#' + schedule.title;
                },
                taskTitle: function () {
                    return '<label><input type="checkbox" />' + me._e('Task') + '</label>';
                },
                allday: function (schedule) {
                    return schedule.title + ' <i class="fa fa-refresh"></i>';
                },
                alldayTitle: function () {
                    return me._e('All Day');
                },
                time: function (schedule) {
                    return schedule.title + ' <i class="fa fa-refresh"></i>' + schedule.start;
                },
                monthMoreTitleDate: function (date) {
                    date = new Date(date);
                    return tui.util.formatDate('DD.MM', date) + '(' + daynames[date.getDay()] + ')';
                },
                monthMoreClose: function () {
                    return '<i class="fa fa-close"></i>';
                },
                monthGridHeader: function (model) {
                    let date = new Date(model.date),
                        template = '<span class="tui-full-calendar-weekday-grid-date">' + date.getDate() + '</span>';
                    if (model.isToday) {
                        template += '<span class="tui-full-calendar-weekday-grid-date-decorator">&nbsp;</span>';
                    }
                    return template;
                },
                monthGridHeaderExceed: function (hiddenSchedules) {
                    return '<span class="calendar-more-schedules">+' + hiddenSchedules + '</span>';
                },

                monthGridFooter: function () {
                    return '<div class="calendar-new-schedule-button">' + me._e('New Schedule') + '</div>';
                },

                monthGridFooterExceed: function (hiddenSchedules) {
                    return '<span class="calendar-footer-more-schedules">' + me._e('See %1$s more events', [
                        hiddenSchedules
                    ]) + '</span>';
                },
                weekDayname: function (dayname) {
                    return '<span class="calendar-week-dayname-name">' +
                        dayname.dayName +
                        '</span><br><span class="calendar-week-dayname-date">' + dayname.date + '</span>';
                },
                monthDayname: function (dayname) {
                    return '<span class="calendar-week-dayname-name">' + dayname.label + '</span>';
                },
                timegridDisplayPrimaryTime: function (time) {
                    return time.hour;
                },
                timegridDisplayTime: function (time) {
                    return time.hour + ':' + time.minutes;
                },
                goingDuration: function (model) {
                    let goingDuration = model.goingDuration,
                        hour = parseInt(goingDuration / SIXTY_MINUTES, 10),
                        minutes = goingDuration % SIXTY_MINUTES;

                    return me._e('GoingTime') + hour + ':' + minutes;
                },
                comingDuration: function (model) {
                    let goingDuration = model.goingDuration,
                        hour = parseInt(goingDuration / SIXTY_MINUTES, 10),
                        minutes = goingDuration % SIXTY_MINUTES;

                    return me._e('ComingTime') + hour + ':' + minutes;
                },
                popupDetailRepeat: function (model) {
                    return model.recurrenceRule;
                },
                popupDetailBody: function (model) {
                    return model.body;
                }
            }
        },

        /**
         * @param {Object} config
         * @return {Object}
         * @private
         */
        _getMonthDefintion: function (config) {
            return {
                daynames: [
                    this._e('Sunday'),
                    this._e('Monday'),
                    this._e('Tuesday'),
                    this._e('Wednesday'),
                    this._e('Thursday'),
                    this._e('Friday'),
                    this._e('Saturday')
                ],
                startDayOfWeek: 1,
                narrowWeekend: true
            }
        },

        /**
         * @param {Object} config
         * @return {Object}
         * @private
         */
        _getWeekDefintion: function (config) {
            return {
                daynames: [
                    this._e('Sunday'),
                    this._e('Monday'),
                    this._e('Tuesday'),
                    this._e('Wednesday'),
                    this._e('Thursday'),
                    this._e('Friday'),
                    this._e('Saturday')
                ],
                startDayOfWeek: 1,
                narrowWeekend: true
            }
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} config
         * @private
         */
        _addHooks: function (calendar, config) {
            let me = this;
            calendar.on({
                'clickSchedule': function (event) {
                    me._addHookOnClickSchedule(calendar, event, config);
                },
                'clickMore': function (event) {
                    me._addHookOnClickMore(calendar, event, config);
                },
                'beforeCreateSchedule': function (event) {
                    me._addHookOnBeforeCreateSchedule(calendar, event, config);
                },
                'beforeUpdateSchedule': function (event) {
                    me._addHookOnBeforeUpdateSchedule(calendar, event, config);
                },
                'beforeDeleteSchedule': function (event) {
                    me._addHookOnBeforeDeleteSchedule(calendar, event, config);
                }
            });

            $(config.target).on('calendar-set-option', function (e, option, callback) {
                me._setCalendarOption(calendar, option, config, callback);
            });
            $(config.target).on('calendar-set-view', function (e, view, callback) {
                me._setCalendarView(calendar, view, config, callback);
            });
            $(config.target).on('calendar-refresh', function (e, callback) {
                me._updateCalendar(calendar, config, callback);
            })
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnClickSchedule: function (calendar, event, config) {
            let schedule = event.schedule;

            // focus the schedule
            if (this.lastClickedSchedule) {
                calendar.updateSchedule(this.lastClickedSchedule.id, this.lastClickedSchedule.calendarId, {
                    isFocused: false
                });
            }
            calendar.updateSchedule(schedule.id, schedule.calendarId, {
                isFocused: true
            });

            this.lastClickedSchedule = schedule;
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnClickMore: function (calendar, event, config) {
            console.log('clickMore', event.date, event.target);
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnBeforeCreateSchedule: function (calendar, event, config) {
            let startDate = sprintf(
                '%s-%s-%s',
                event.start.getFullYear(),
                ('00'+event.start.getMonth()).slice(-2),
                ('00'+event.start.getDay()).slice(-2)
            );
            let endDate = sprintf(
                '%s-%s-%s',
                event.end.getFullYear(),
                ('00'+event.end.getMonth()).slice(-2),
                ('00'+event.end.getDay()).slice(-2)
            );
            
            let schedule = {
                calendarId: event.calendarId,
                title: event.title,
                state: event.state,
                category: event.raw.class,
                location: event.location,
                start: startDate,
                end: endDate,
                isAllDay: event.isAllDay || false,
                isReadOnly: event.isReadOnly || false
            };

            let payload = $.extend({}, {
                action: config.actionCreateSchedule
            }, schedule || {});

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                //contentType: 'application/json',
                data: payload,
                success: function(data, status, event){
                    console.log('finished create schedules success', event, status, data);
                    calendar.createSchedules([schedule]);
                },
                error: function(event, status, error){
                    console.log('finished create schedules success', event, status, error);

                    $(config.modalSelector).modal({
                        escapeClose: true,
                        clickClose: true,
                        showClose: true
                    });
                },
                complete: function(event, status) {
                    console.log('finished create schedules callback', event, status);
                }
            });
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnBeforeUpdateSchedule: function (calendar, event, config) {
            console.log('beforeUpdateSchedule');
            event.schedule.start = event.start;
            event.schedule.end = event.end;
            calendar.updateSchedule(event.schedule.id, event.schedule.calendarId, event.schedule);
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnBeforeDeleteSchedule: function (calendar, event, config) {
            console.log('beforeDeleteSchedule');
            calendar.deleteSchedule(e.schedule.id, e.schedule.calendarId);
        },
    };


    /**
     * Creates new instance
     * @param {Object} options
     * @returns {$.fn}
     */
    $.fn.microErpPlanningCalendar = function (options) {
        $.microErpPlanningCalendar.create(options);
        return this;
    };
}(jQuery));