## Options

### format

	Default: false

See [momentjs' docs](http://momentjs.com/docs/#/displaying/format/) for valid formats. Format also dictates what components are shown, e.g. `MM/dd/YYYY` will not display the time picker.

----------------------


### dayViewHeaderFormat

	Default: 'MMMM YYYY'

Changes the heading of the datepicker when in "days" view.

![Datepicker Header](img/dpheader.png)

----------------------


### extraFormats

	Default: false

Allows for several input formats to be valid. See [this PR](https://github.com/Eonasdan/bootstrap-datetimepicker/pull/666).

----------------------


### stepping

	Default: 1

Number of minutes the up/down arrow's will move the minutes value in the time picker

----------------------


### minDate

	Default: false
    Accepts: date, moment, string

Prevents date/time selections before this date.

#### `minDate` will override `defaultDate` and `useCurrent` if either of these settings are the same day since both options are invalid according to the rules you've selected.

----------------------


### maxDate

	Default: false
    Accepts: date, moment, string

Prevents date/time selections after this date.

#### `maxDate` will override `defaultDate` and `useCurrent` if either of these settings are the same day since both options are invalid according to the rules you've selected.

----------------------


### useCurrent

	Default: true

On show, will set the picker to the current date/time

----------------------


### collapse

	Default: true

Using a Bootstraps collapse to switch between date/time pickers.

----------------------


### locale

	Default: moment.locale()
    Accepts: string, moment.local('locale')

See [momentjs](https://github.com/moment/moment/tree/develop/locale) for valid locales.

You must include `moment-with-locales.js` or a local js file.

----------------------


### defaultDate

	Default: false
    Accepts: date, moment, string

Sets the picker default date/time. Overrides `useCurrent`

----------------------


### disabledDates

	Default: false
    Accepts: array of [date, moment, string]

Disables selection of dates in the array, e.g. holidays

----------------------


### enabledDates

	Default: false
    Accepts: array of [date, moment, string]

Disables selection of dates **NOT** in the array, e.g. holidays

----------------------


### icons

	Default: {
                time: 'glyphicon glyphicon-time',
                date: 'glyphicon glyphicon-calendar',
                up: 'glyphicon glyphicon-chevron-up',
                down: 'glyphicon glyphicon-chevron-down',
                previous: 'glyphicon glyphicon-chevron-left',
                next: 'glyphicon glyphicon-chevron-right',
                today: 'glyphicon glyphicon-screenshot',
                clear: 'glyphicon glyphicon-trash',
                close: 'glyphicon glyphicon-remove'
            }
    Accepts: object with all or some of the parameters above

Change the default icons for the pickers functions.

### useStrict

	Default: false

Defines if moment should use strict date parsing when considering a date to be valid

----------------------


### sideBySide

	Default: false

Shows the picker side by side when using the time and date together.

![SideBySide](img/sideBySide.png)

----------------------


### daysOfWeekDisabled

	Default: []
    Accepts: array of numbers from 0-6

Disables the section of days of the week, e.g. weekends.

----------------------


### calendarWeeks

	Default: false

Shows the week of the year to the left of first day of the week.

![calendarWeek](img/calendarWeeks.png)

----------------------


### viewMode

	Default: 'days'
    Accepts: 'decades','years','months','days'

The default view to display when the picker is shown.

**Note**: To limit the picker to selecting, for instance the year and month, use `format: MM/YYYY`

----------------------


### toolbarPlacement

	Default: 'default'
    Accepts: 'default', 'top', 'bottom'

Changes the placement of the icon toolbar.

![toolbarPlacement](img/toolbarPlacement.png)

----------------------


### showTodayButton

	Default: false

Show the "Today" button in the icon toolbar.

Clicking the "Today" button will set the calendar view and set the date to `now`.

----------------------


### showClear

	Default: false

Show the "Clear" button in the icon toolbar.

Clicking the "Clear" button will set the calendar to null.

----------------------

### showClose

	Default: false

Show the "Close" button in the icon toolbar.

Clicking the "Close" button will call `hide()`

----------------------


### widgetPositioning

	Default: {
                horizontal: 'auto'
                vertical: 'auto'
             }
    Accepts: object with the all or one of the parameters above
             horizontal: 'auto', 'left', 'right'
             vertical: 'auto', 'top', 'bottom'

### widgetParent

	Default: null
    Accepts: string or jQuery object

On picker show, places the widget at the identifier (string) or jQuery object **if** the element has css `position: 'relative'`

----------------------


### keepOpen

	Default: false

Will cause the date picker to stay open after selecting a date if no time components are being used.

----------------------


### inline

	Default: false

Will display the picker inline without the need of a input field. This will also hide borders and shadows.

----------------------


### keepInvalid

	Default: false

Will cause the date picker to **not** revert or overwrite invalid dates.

----------------------


### keyBinds

	Default: up: function (widget) {
                if (widget.find('.datepicker').is(':visible')) {
                    this.date(this.date().clone().subtract(7, 'd'));
                } else {
                    this.date(this.date().clone().add(1, 'm'));
                }
            },
            down: function (widget) {
                if (!widget) {
                    this.show();
                }
                else if (widget.find('.datepicker').is(':visible')) {
                    this.date(this.date().clone().add(7, 'd'));
                } else {
                    this.date(this.date().clone().subtract(1, 'm'));
                }
            },
            'control up': function (widget) {
                if (widget.find('.datepicker').is(':visible')) {
                    this.date(this.date().clone().subtract(1, 'y'));
                } else {
                    this.date(this.date().clone().add(1, 'h'));
                }
            },
            'control down': function (widget) {
                if (widget.find('.datepicker').is(':visible')) {
                    this.date(this.date().clone().add(1, 'y'));
                } else {
                    this.date(this.date().clone().subtract(1, 'h'));
                }
            },
            left: function (widget) {
                if (widget.find('.datepicker').is(':visible')) {
                    this.date(this.date().clone().subtract(1, 'd'));
                }
            },
            right: function (widget) {
                if (widget.find('.datepicker').is(':visible')) {
                    this.date(this.date().clone().add(1, 'd'));
                }
            },
            pageUp: function (widget) {
                if (widget.find('.datepicker').is(':visible')) {
                    this.date(this.date().clone().subtract(1, 'M'));
                }
            },
            pageDown: function (widget) {
                if (widget.find('.datepicker').is(':visible')) {
                    this.date(this.date().clone().add(1, 'M'));
                }
            },
            enter: function () {
                this.hide();
            },
            escape: function () {
                this.hide();
            },
            'control space': function (widget) {
                if (widget.find('.timepicker').is(':visible')) {
                    widget.find('.btn[data-action="togglePeriod"]').click();
                }
            },
            t: function () {
                this.date(moment());
            },
            'delete': function () {
                this.clear();
            }

Allows for custom events to fire on keyboard press.

----------------------


### debug 

<small>4.7.14</small>

	Default: false

Will cause the date picker to stay open after a `blur` event.

----------------------


### ignoreReadonly

<small>4.7.14</small>

	Default: false

Allow date picker show event to fire even when the associated input element has the `readonly="readonly"`property.

----------------------


### disabledTimeIntervals 

<small>4.14.30</small>

	Default: false

Disables time selection between the given `moments`

----------------------


### allowInputToggle 

<small>4.14.30</small>

	Default: false

If `true`, the picker will show on textbox focus and icon click when used in a button group

----------------------


### focusOnShow 

<small>4.14.30</small>

	Default: true

If `false`, the textbox will not be given focus when the picker is shown

----------------------


### enabledHours 

<small>4.14.30</small>

	Default: false

Will allow or disallow hour selections (much like `disabledTimeIntervals`) but will affect all days

----------------------


### disabledHours 

<small>4.14.30</small>

	Default: false

Will allow or disallow hour selections (much like `disabledTimeIntervals`) but will affect all days

----------------------


### viewDate 

<small>4.14.30</small>

	Default: false

This will change the `viewDate` without changing or setting the selected date.

----------------------


### tooltips 

<small>4.15.35</small>

```
tooltips: {
    today: 'Go to today',
    clear: 'Clear selection',
    close: 'Close the picker',
    selectMonth: 'Select Month',
    prevMonth: 'Previous Month',
    nextMonth: 'Next Month',
    selectYear: 'Select Year',
    prevYear: 'Previous Year',
    nextYear: 'Next Year',
    selectDecade: 'Select Decade',
    prevDecade: 'Previous Decade',
    nextDecade: 'Next Decade',
    prevCentury: 'Previous Century',
    nextCentury: 'Next Century'
}
```

This will change the `tooltips` over each icon to a custom string
