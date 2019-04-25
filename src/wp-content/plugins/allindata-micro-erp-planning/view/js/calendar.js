(function ($) {
    $.microErpPlanningCalendar = {
        translatedLabels: {},

        lastClickedSchedule: null,

        defaults: {
            calendarId: 1,
            target: '#calendar',
            modalSelector: '#calendar_modal',
            actionCreateSchedule: '',
            actionUpdateSchedule: '',
            actionDeleteSchedule: '',
            labels: {
                'Calendar Range': '%1$s to %2$s',
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
            initialDate: moment(),
            renderDateSelector: '.calendar-render-range',
            formats: {
                datetime: 'DD.MM.YYYY HH:mm:ss',
                date: 'DD.MM.YYYY',
                time: 'HH:mm:ss',
                calendarRangeStart: 'DD.MM',
                calendarRangeEnd: 'DD.MM.YYYY',
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
                week: {},
                timezones: [{
                    timezoneOffset: (new Date()).getTimezoneOffset(),
                    displayLabel: 'GMT+02:00',
                    tooltip: 'Europe/Berlin'
                }],
                usageStatistics: false
            },
            callbacks: {
                today: function() {},
                prev: function() {},
                next: function() {},
            },
            currentView: 'month',
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
            config.calendarOptions.date = config.initialDate.format(config.formats.datetime);

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
            let me = this,
                Calendar = tui.Calendar,
                calendarInstance = new Calendar(config.target, config.calendarOptions);

            calendarInstance.id = config.calendarId;
            calendarInstance.createSchedules(config.schedules);
            me._refreshScheduleVisibility(calendarInstance, config, function () {
                callback.call(me, calendarInstance, config);
            });
        },

        /**
         * @param {Calendar} calendar
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _updateCalendar: function (calendar, config, callback = function () {
        }) {
            let me = this;
            calendar.changeView(config.currentView, true);
            me._refreshScheduleVisibility(calendar, config, function () {
                callback.call(me, calendar, config);
            });
        },

        /**
         * @param {Calendar} calendar
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _refreshScheduleVisibility: function (calendar, config, callback = function () {
        }) {
            let me = this,
                calendarRangeLabel;

            calendar.toggleSchedules(config.calendarId, false, false);
            calendar.render(true);

            calendarRangeLabel = me._e('Calendar Range', [
                moment(calendar.getDateRangeStart().toDate()).format(config.formats.calendarRangeStart),
                moment(calendar.getDateRangeEnd().toDate()).format(config.formats.calendarRangeEnd)
            ]);
    console.log(calendarRangeLabel);
            $(config.renderDateSelector).html(calendarRangeLabel);

            callback.call(this, calendar, config);
        },

        /**
         * @param {Calendar} calendar
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
         * @param {Calendar} calendar
         * @param {string} view
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _setCalendarView: function (calendar, view, config, callback = function () {
        }) {
            calendar.changeView(view, true);
            config.currentView = view;
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
                    return schedule.title + ' <i class="fa fa-refresh"></i> ' +
                        moment(schedule.start.toDate()).format(config.formats.time);
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
                    return '<div class="calendar-new-schedule-button"><i class="fa fa-plus"></i></div>';
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
                },
                popupDetailDate: function(isAllDay, start, end) {
                    let startDate = moment(start.toDate()),
                        endDate =  moment(end.toDate()),
                        isSameDate = startDate.format(config.formats.date) === endDate.format(config.formats.date),
                        endFormat = (isSameDate ? '' : config.formats.date) + ' ' + config.formats.time;

                    if (isAllDay) {
                        return startDate.format(config.formats.date) + (isSameDate ? '' : ' - ' + endDate.format(config.formats.date));
                    }

                    return (startDate.format(config.formats.datetime) + ' - ' + endDate.format(endFormat));
                },
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
         * @param {Calendar} calendar
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
            });
            $(config.target).on('calendar-today', function () {
                calendar.today();
                me._updateCalendar(calendar, config, config.callbacks.today);
            });
            $(config.target).on('calendar-prev', function () {
                calendar.prev();
                me._updateCalendar(calendar, config, config.callbacks.prev);
            });
            $(config.target).on('calendar-next', function () {
                calendar.next();
                me._updateCalendar(calendar, config, config.callbacks.next);
            });
        },

        /**
         * @param {Calendar} calendar
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
         * @param {Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnClickMore: function (calendar, event, config) {
            console.log('clickMore', event.date, event.target);
        },

        /**
         * @param {Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnBeforeCreateSchedule: function (calendar, event, config) {
            let me = this,
                startDate = this._mapDate(event.start),
                endDate = this._mapDate(event.end),
                schedule = $.extend(true, this._getSchedulePrototype(), event);

            schedule.calendarId = 1;
            schedule.category = 'time';
            schedule.start = startDate;
            schedule.end = endDate;

            let payload = {
                action: config.actionCreateSchedule,
                schedule: schedule || {}
            };

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    schedule.id = JSON.parse(data);
                    calendar.createSchedules([schedule]);
                },
                error: function () {
                    me._throwModalError('Could not create schedule', config);
                }
            });
        },

        /**
         * @param {Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnBeforeUpdateSchedule: function (calendar, event, config) {
            let me = this,
                startDate = this._mapDate(event.start),
                endDate = this._mapDate(event.end),
                schedule = $.extend(true, this._getSchedulePrototype(), event.schedule);
            schedule.calendarId = 1;
            schedule.category = 'time';
            schedule.start = startDate;
            schedule.end = endDate;

            let payload = {
                action: config.actionUpdateSchedule,
                schedule: schedule || {}
            };

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    let result = JSON.parse(data);
                    if (!result) {
                        me._throwModalError('Could not update schedule', config);
                        return;
                    }
                    calendar.updateSchedule(schedule.id, schedule.calendarId, schedule);
                },
                error: function () {
                    me._throwModalError('Could not update schedule', config);
                }
            });
        },

        /**
         * @param {Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @private
         */
        _addHookOnBeforeDeleteSchedule: function (calendar, event, config) {
            let me = this;

            let payload = {
                action: config.actionDeleteSchedule,
                scheduleId: event.schedule.id
            };

            $.ajax({
                type: 'POST',
                url: wp_ajax_action.action_url,
                data: payload,
                success: function (data) {
                    let result = JSON.parse(data);
                    if (!result) {
                        me._throwModalError('Could not delete schedule', config);
                        return;
                    }
                    calendar.deleteSchedule(event.schedule.id, event.schedule.calendarId);
                },
                error: function () {
                    me._throwModalError('Could not delete schedule', config);
                }
            });
        },

        /**
         * @param {string} errorMessage
         * @param {Object} config
         * @private
         */
        _throwModalError: function (errorMessage, config) {
            $(config.modalSelector).modal({
                escapeClose: true,
                clickClose: true,
                showClose: true
            });
        },

        /**
         * @param {TZDate} date
         * @return {string}
         * @private
         */
        _mapDate: function (date) {
            let yyyy = date.getFullYear().toString(),
                mm = (date.getMonth() + 1).toString(),
                dd = date.getDate().toString(),
                hh = date.getHours().toString(),
                ii = date.getMinutes().toString(),
                ss = date.getSeconds().toString();

            return yyyy + '-' + (mm[1] ? mm : "0" + mm[0]) + '-' + (dd[1] ? dd : "0" + dd[0]) + ' ' +
                (hh[1] ? hh : "0" + hh[0]) + ':' + (ii[1] ? ii : "0" + ii[0]) + ':' + (ss[1] ? ss : "0" + ss[0]);
        },

        /**
         * @return {{borderColor: null, dueDateClass: string, color: null, customStyle: string, start: null, raw: {hasRecurrenceRule: boolean, creator: {phone: string, name: string, company: string, avatar: string, email: string}, memo: string, location: null, hasToOrCc: boolean, class: string}, recurrenceRule: string, isVisible: boolean, title: null, body: null, isPending: boolean, isFocused: boolean, comingDuration: number, goingDuration: number, isAllday: boolean, isReadOnly: boolean, calendarId: null, bgColor: null, dragBgColor: null, end: null, id: null, category: string}}
         * @private
         */
        _getSchedulePrototype: function () {
            return {
                id: null,
                calendarId: null,
                title: null,
                body: null,
                isAllday: false,
                start: null,
                end: null,
                category: '',
                dueDateClass: '',
                isFocused: false,
                isPending: false,
                isVisible: true,
                isReadOnly: false,
                goingDuration: 0,
                comingDuration: 0,
                recurrenceRule: '',
                color: null,
                bgColor: null,
                dragBgColor: null,
                borderColor: null,
                customStyle: '',
                raw: {
                    memo: '',
                    hasToOrCc: false,
                    hasRecurrenceRule: false,
                    location: null,
                    class: 'public', // or 'private'
                    creator: {
                        name: '',
                        avatar: '',
                        company: '',
                        email: '',
                        phone: ''
                    }
                }
            }
        }
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