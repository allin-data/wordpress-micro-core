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
                'Saturday': 'Saturday',
                'Error Message': 'Error Message',
                'Could not create schedule': 'Could not create schedule',
                'Could not update schedule': 'Could not update schedule',
                'Could not delete schedule': 'Could not delete schedule'
            },
            initialDate: moment(),
            renderDateSelector: '.calendar-render-range',
            formats: {
                datetime: 'DD.MM.YYYY HH:mm',
                date: 'DD.MM.YYYY',
                time: 'HH:mm',
                calendarRangeStart: 'DD.MM',
                calendarRangeEnd: 'DD.MM.YYYY',
                map: 'YYYY-MM-DD HH:mm:ss'
            },
            customScheduleCreationGuide: {
                modalTemplateSelector: '#calendar_modal_schedule_creation_guide',
                submitButtonSelector: '#calendar_modal_schedule_creation_guide .btn-schedule-creation-guide-save',
                closeButtonSelector: '#calendar_modal_schedule_creation_guide .btn-schedule-creation-guide-close',
            },
            resources: {},
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
                month: {
                    startDayOfWeek: 1,
                    isAlways6Week: false,
                    narrowWeekend: true
                },
                week: {
                    startDayOfWeek: 1,
                    narrowWeekend: true,
                    hourStart: 0,
                    hourEnd: 24
                },
                timezones: [{
                    timezoneOffset: (new Date()).getTimezoneOffset(),
                    displayLabel: 'GMT+02:00',
                    tooltip: 'Europe/Berlin'
                }],
                usageStatistics: false,
                theme: {
                    'common.border': '1px solid #e5e5e5',
                    'common.backgroundColor': 'white',
                    'common.holiday.color': '#ff4040',
                    'common.saturday.color': '#333',
                    'common.dayname.color': '#333',
                    'common.today.color': '#333',

                    // creation guide style
                    'common.creationGuide.backgroundColor': 'rgba(81, 92, 230, 0.05)',
                    'common.creationGuide.border': '1px solid #515ce6',

                    // month header 'dayname'
                    'month.dayname.height': '31px',
                    'month.dayname.borderLeft': '1px solid #e5e5e5',
                    'month.dayname.paddingLeft': '10px',
                    'month.dayname.paddingRight': '10px',
                    'month.dayname.backgroundColor': 'inherit',
                    'month.dayname.fontSize': '12px',
                    'month.dayname.fontWeight': 'normal',
                    'month.dayname.textAlign': 'left',

                    // month day grid cell 'day'
                    'month.holidayExceptThisMonth.color': 'rgba(255, 64, 64, 0.4)',
                    'month.dayExceptThisMonth.color': 'rgba(51, 51, 51, 0.4)',
                    'month.weekend.backgroundColor': 'inherit',
                    'month.day.fontSize': '14px',

                    // month schedule style
                    'month.schedule.borderRadius': '2px',
                    'month.schedule.height': '24px',
                    'month.schedule.marginTop': '2px',
                    'month.schedule.marginLeft': '8px',
                    'month.schedule.marginRight': '8px',

                    // month more view
                    'month.moreView.border': '1px solid #d5d5d5',
                    'month.moreView.boxShadow': '0 2px 6px 0 rgba(0, 0, 0, 0.1)',
                    'month.moreView.backgroundColor': 'white',
                    'month.moreView.paddingBottom': '17px',
                    'month.moreViewTitle.height': '44px',
                    'month.moreViewTitle.marginBottom': '12px',
                    'month.moreViewTitle.backgroundColor': 'inherit',
                    'month.moreViewTitle.borderBottom': 'none',
                    'month.moreViewTitle.padding': '12px 17px 0 17px',
                    'month.moreViewList.padding': '0 17px',

                    // week header 'dayname'
                    'week.dayname.height': '42px',
                    'week.dayname.borderTop': '1px solid #e5e5e5',
                    'week.dayname.borderBottom': '1px solid #e5e5e5',
                    'week.dayname.borderLeft': 'inherit',
                    'week.dayname.paddingLeft': '0',
                    'week.dayname.backgroundColor': 'inherit',
                    'week.dayname.textAlign': 'left',
                    'week.today.color': '#333',
                    'week.pastDay.color': '#bbb',

                    // week vertical panel 'vpanel'
                    'week.vpanelSplitter.border': '1px solid #e5e5e5',
                    'week.vpanelSplitter.height': '3px',

                    // week daygrid 'daygrid'
                    'week.daygrid.borderRight': '1px solid #e5e5e5',
                    'week.daygrid.backgroundColor': 'inherit',

                    'week.daygridLeft.width': '72px',
                    'week.daygridLeft.backgroundColor': 'inherit',
                    'week.daygridLeft.paddingRight': '8px',
                    'week.daygridLeft.borderRight': '1px solid #e5e5e5',

                    'week.today.backgroundColor': 'rgba(81, 92, 230, 0.05)',
                    'week.weekend.backgroundColor': 'inherit',

                    // week timegrid 'timegrid'
                    'week.timegridLeft.width': '72px',
                    'week.timegridLeft.backgroundColor': 'inherit',
                    'week.timegridLeft.borderRight': '1px solid #e5e5e5',
                    'week.timegridLeft.fontSize': '11px',
                    'week.timegridLeftTimezoneLabel.height': '40px',
                    'week.timegridLeftAdditionalTimezone.backgroundColor': 'white',

                    'week.timegridOneHour.height': '52px',
                    'week.timegridHalfHour.height': '26px',
                    'week.timegridHalfHour.borderBottom': 'none',
                    'week.timegridHorizontalLine.borderBottom': '1px solid #e5e5e5',

                    'week.timegrid.paddingRight': '8px',
                    'week.timegrid.borderRight': '1px solid #e5e5e5',
                    'week.timegridSchedule.borderRadius': '2px',
                    'week.timegridSchedule.paddingLeft': '2px',

                    // week time
                    'week.currentTime.color': '#515ce6',
                    'week.currentTime.fontSize': '11px',
                    'week.currentTime.fontWeight': 'normal',

                    'week.pastTime.color': '#bbb',
                    'week.pastTime.fontWeight': 'normal',

                    'week.futureTime.color': '#333',
                    'week.futureTime.fontWeight': 'normal',

                    'week.currentTimeLinePast.border': '1px dashed #515ce6',
                    'week.currentTimeLineBullet.backgroundColor': '#515ce6',
                    'week.currentTimeLineToday.border': '1px solid #515ce6',
                    'week.currentTimeLineFuture.border': 'none',

                    // week creation guide style
                    'week.creationGuide.color': '#515ce6',
                    'week.creationGuide.fontSize': '11px',
                    'week.creationGuide.fontWeight': 'bold',

                    // week daygrid schedule style
                    'week.dayGridSchedule.borderRadius': '2px',
                    'week.dayGridSchedule.height': '24px',
                    'week.dayGridSchedule.marginTop': '2px',
                    'week.dayGridSchedule.marginLeft': '8px',
                    'week.dayGridSchedule.marginRight': '8px'
                }
            },
            callbacks: {
                today: function () {
                },
                prev: function () {
                },
                next: function () {
                },
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
                Calendar,
                calendarInstance;

            Calendar = tui.Calendar;
            calendarInstance = new Calendar(config.target, config.calendarOptions);

            calendarInstance.id = config.calendarId;
            calendarInstance.createSchedules(config.schedules);
            me._refreshScheduleVisibility(calendarInstance, config, function () {
                callback.call(me, calendarInstance, config);
            });
        },

        /**
         * @param {tui.Calendar} calendar
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
         * @param {tui.Calendar} calendar
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
            $(config.renderDateSelector).html(calendarRangeLabel);

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

                popupDetailDate: function (isAllDay, start, end) {
                    let startDate = moment(start.toDate()),
                        endDate = moment(end.toDate()),
                        isSameDate = startDate.format(config.formats.date) === endDate.format(config.formats.date),
                        endFormat = (isSameDate ? '' : config.formats.date) + ' ' + config.formats.time;

                    if (isAllDay) {
                        return startDate.format(config.formats.date) + (isSameDate ? '' : ' - ' + endDate.format(config.formats.date));
                    }

                    return (startDate.format(config.formats.datetime) + ' - ' + endDate.format(endFormat));
                },
                popupSave: function () {
                    return 'Save';
                },
                popupUpdate: function () {
                    return 'Update';
                },
                popupDetailLocation: function (schedule) {
                    return 'Location : ' + schedule.location;
                },
                popupDetailUser: function (schedule) {
                    return 'User : ' + (schedule.attendees || []).join(', ');
                },
                popupDetailState: function (schedule) {
                    return 'State : ' + schedule.state || 'Busy';
                },
                popupDetailRepeat: function (schedule) {
                    return 'Repeat : ' + schedule.recurrenceRule;
                },
                popupDetailBody: function (schedule) {
                    return 'Body : ' + schedule.body;
                },
                popupEdit: function () {
                    return 'Edit';
                },
                popupDelete: function () {
                    return 'Delete';
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
                ]
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
                ]
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
                'clickDayname': function (event) {
                    console.log('clickDayname');
                },
                'beforeCreateSchedule': function (event) {
                    event.start = me._mapDate(event.start, config);
                    event.end = me._mapDate(event.end, config);
                    if (true === config.calendarOptions.useCreationPopup) {
                        // render default schedule creator
                        me._addHookOnBeforeCreateSchedule(calendar, event, config);
                        return;
                    }

                    // render custom schedule creator
                    me._renderCustomScheduleCreationGuide(calendar, event, config,
                        function (calendar, schedule, config, callback = function () {
                        }) {
                            me._addHookOnBeforeCreateSchedule(calendar, schedule, config, callback);
                        });
                },
                'beforeUpdateSchedule': function (event) {
                    if (!event.start) {
                        event.start = event.schedule.start;
                    }
                    if (!event.end) {
                        event.end = event.schedule.end;
                    }
                    event.start = me._mapDate(event.start, config);
                    event.end = me._mapDate(event.end, config);

                    if (true === config.calendarOptions.useCreationPopup) {
                        me._addHookOnBeforeUpdateSchedule(calendar, event.schedule, config);
                        return;
                    }

                    // render custom schedule creator
                    me._renderCustomScheduleCreationGuide(calendar, event, config,
                        function (calendar, schedule, config, callback = function () {
                        }) {
                            me._addHookOnBeforeUpdateSchedule(calendar, schedule, config, callback);
                        });
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
         * @param {tui.Calendar} calendar
         * @param {Object} event
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _renderCustomScheduleCreationGuide: function (calendar, event, config, callback = function () {
        }) {
            let me = this,
                modal;

            modal = $(config.customScheduleCreationGuide.modalTemplateSelector);

            $(config.customScheduleCreationGuide.submitButtonSelector)
                .off('click')
                .on('click', function () {
                    let formData,
                        schedule;

                    // @TODO fetch data from custom schedule creation form
                    formData = {
                        id: event.schedule.id,
                        start: event.start,
                        end: event.end,
                        isAllDay: event.isAllDay,
                        title: modal.find('input[name="name"]').val()
                    };
                    formData = me._merge(event.schedule, formData);
                    schedule = me._merge(me._getSchedulePrototype(), formData);

                    callback.call(me, calendar, schedule, config, function (calendar, schedule, config) {
                        $(config.customScheduleCreationGuide.closeButtonSelector).trigger('click');
                    });
                });

            // does prefetched data exist?
            if (event.schedule) {
                modal.find('input[name="name"]').val(event.schedule.title);
            }

            modal.modal('show');
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
         * @param {Object} schedule
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _addHookOnBeforeCreateSchedule: function (calendar, schedule, config, callback = function () {
        }) {
            let me = this;

            schedule = me._merge(me._getSchedulePrototype(), schedule);
            schedule.calendarId = 1;
            schedule.category = 'time';

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
                    callback.call(me, calendar, schedule, config);
                },
                error: function () {
                    me._throwModalError(me._e('Could not create schedule'), config);
                }
            });
        },

        /**
         * @param {tui.Calendar} calendar
         * @param {Object} schedule
         * @param {Object} config
         * @param {Function} callback
         * @private
         */
        _addHookOnBeforeUpdateSchedule: function (calendar, schedule, config, callback = function () {
        }) {
            let me = this;

            schedule = me._merge(me._getSchedulePrototype(), schedule);
            schedule.calendarId = 1;
            schedule.category = 'time';

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
                        me._throwModalError(me._e('Could not update schedule'), config);
                        return;
                    }
                    calendar.updateSchedule(schedule.id, schedule.calendarId, schedule);
                    callback.call(me, calendar, schedule, config);
                },
                error: function () {
                    me._throwModalError(me._e('Could not update schedule'), config);
                }
            });
        },

        /**
         * @param {tui.Calendar} calendar
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
                        me._throwModalError(me._e('Could not delete schedule'), config);
                        return;
                    }
                    calendar.deleteSchedule(event.schedule.id, event.schedule.calendarId);
                },
                error: function () {
                    me._throwModalError(me._e('Could not delete schedule'), config);
                }
            });
        },

        /**
         * @param {String} errorMessage
         * @param {Object} config
         * @param {String} title
         * @private
         */
        _throwModalError: function (errorMessage, config, title = null) {
            let me = this,
                titleLabel = title || me._e('Error Message');
            $(config.modalSelector + ' .modal-title').html(titleLabel);
            $(config.modalSelector + ' .modal-body').html(errorMessage);
            $(config.modalSelector).modal('show');
        },

        /**
         * @param {TZDate} date
         * @param {Object} config
         * @return {string}
         * @private
         */
        _mapDate: function (date, config) {
            return moment(date.toDate()).format(config.formats.map);
        },

        /**
         * Merge objects, only with target properties. Target object will not be modified.
         * @param {Object} target
         * @param {Object} source
         * @return {Object} Merged Object
         * @private
         */
        _merge: function (target, source) {
            let newTarget = Object.assign({}, target);
            Object.keys(newTarget).map(function (property) {
                if (source[property]) {
                    newTarget[property] = source[property]
                }
            });
            return newTarget;
        },

        /**
         * @return {{borderColor: null, dueDateClass: string, color: null, customStyle: string, start: null, raw: {hasRecurrenceRule: boolean, creator: {phone: string, name: string, company: string, avatar: string, email: string}, memo: string, location: null, hasToOrCc: boolean, class: string}, recurrenceRule: string, isVisible: boolean, title: null, body: null, isPending: boolean, isFocused: boolean, comingDuration: number, goingDuration: number, isAllDay: boolean, isReadOnly: boolean, calendarId: null, bgColor: null, dragBgColor: null, end: null, id: null, category: string}}
         * @private
         */
        _getSchedulePrototype: function () {
            return {
                id: null,
                calendarId: null,
                title: null,
                body: null,
                isAllDay: false,
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