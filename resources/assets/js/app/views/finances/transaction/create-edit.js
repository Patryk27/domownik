module.exports = (function() {

  /**
   * Pointer to the calendar.
   * @type {jQuery}
   */
  var calendar = null;

  /**
   * List of days in format YYYY-MM-DD containing days selected by user.
   * @type {string[]}
   */
  var calendarDates = {
    'transaction-periodicity-one-shot': [],
    'transaction-periodicity-yearly': [],
  };

  /**
   * Returns type of currently selected periodicity.
   * @returns {string}
   */
  function getSelectedPeriodicityType() {
    return $('#transactionPeriodicityType').val();
  }

  /**
   * Refreshes the calendar, effectively re-rendering all the days within.
   */
  function refreshCalendar() {
    var calendarData = calendar.data('calendar');
    calendarData.setDataSource(calendarData.getDataSource());
  }

  /**
   * Initializes the view.
   */
  function initialize() {
    $('#transactionValueType').change(function() {
      var activeTransactionValueType = $(this).val();

      $('.transaction-value-wrapper').hide().fieldsRequired(false);
      $('.transaction-value-wrapper[data-transaction-value-type="' + activeTransactionValueType + '"]').show().fieldsRequired(true);
    }).trigger('change');

    $('#transactionPeriodicityType').change(function() {
      var selectedPeriodicity = $(this).val();

      $('.transaction-periodicity').hide();
      $('.transaction-periodicity[data-transaction-periodicity-type="' + selectedPeriodicity + '"]').show();

      switch (selectedPeriodicity) {
          // both one-shot and yearly periodicity types share very similar calendar initialization code, so that
          // we can re-use it.
        case 'transaction-periodicity-one-shot':
        case 'transaction-periodicity-yearly':
          //noinspection JSUnusedGlobalSymbols
          var calendarOptions = {
            language: App.Configuration.getLocale(),

            clickDay: function(e) {
              var periodicityCalendarDates = calendarDates[getSelectedPeriodicityType()];

              var date = moment(e.date).format('YYYY-MM-DD');
              var dateIdx = periodicityCalendarDates.indexOf(date);

              if (dateIdx >= 0) {
                periodicityCalendarDates.splice(dateIdx, 1);
              } else {
                periodicityCalendarDates.push(date);
              }

              refreshCalendar();
            },

            customDayRenderer: function(element, date) {
              var dateString = moment(date).format('YYYY-MM-DD');
              var periodicityCalendarDates = calendarDates[getSelectedPeriodicityType()];

              if (periodicityCalendarDates.indexOf(dateString) >= 0) {
                $(element).addClass('selected-day');
              }
            },
          };

          /**
           * Initialize the calendar.
           */
          switch (selectedPeriodicity) {
            case 'transaction-periodicity-one-shot':
              calendar = $('#transaction-periodicity-one-shot .periodicity-wrapper');
              break;

            case 'transaction-periodicity-yearly':
              calendar = $('#transaction-periodicity-yearly .periodicity-wrapper');

              var now = new Date();
              calendarOptions.minDate = new Date(now.getUTCFullYear(), 0, 1, 0, 0, 0, 0);
              calendarOptions.maxDate = new Date(now.getUTCFullYear(), 11, 31, 23, 59, 59, 0);

              break;
          }

          // a bit of hack for the calendar not to be created many times
          // (as it doubles/triples/etc. also the handlers)
          if (calendar.find('.months-container').length === 0) {
            calendar.calendar(calendarOptions);
            calendar.show();
          }

          refreshCalendar();

          if (selectedPeriodicity === 'transaction-periodicity-yearly') {
            /**
             * When selecting periodical transaction days, it does not make sense to show year because the
             * transaction applies to every year, so that we hide it.
             */
            $('.calendar-header').hide();
          }

          break;
      }
    }).trigger('change');

    $('#transactionForm').ajaxForm({
      prepareData: function(data) {
        data.calendarDates = calendarDates[getSelectedPeriodicityType()];
        return data;
      },
    });
  }

  //noinspection JSUnusedGlobalSymbols
  return {
    initializeView: function() {
      $(initialize);
      return this;
    },

    Periodicity: {

      OneShot: {

        /**
         * @param {String[]} dates
         * @returns {exports}
         */
        prepare: function(dates) {
          var periodicityType = getSelectedPeriodicityType();
          calendarDates[periodicityType] = dates;

          refreshCalendar();

          return this;
        },

      },

      Weekly: {

        /**
         * @param {Number[]} weekdays
         * @returns {exports}
         */
        prepare: function(weekdays) {
          $('input[name="transactionPeriodicityWeeklyDays[]"]').each(function() {
            var isValid = weekdays.indexOf(parseInt($(this).val())) >= 0;
            $(this).prop('checked', isValid);
          });

          return this;
        },

      },

      Monthly: {

        /**
         * @param {Number[]} monthDays
         * @returns {exports}
         */
        prepare: function(monthDays) {
          $('input[name="transactionPeriodicityMonthlyDays[]"]').each(function() {
            var isValid = monthDays.indexOf(parseInt($(this).val())) >= 0;
            $(this).prop('checked', isValid);
          });

          return this;
        },

      },

      Yearly: {

        /**
         * @param {Number[][]} dates
         * @returns {exports}
         */
        prepare: function(dates) {
          var periodicityType = getSelectedPeriodicityType();

          var year = new String(new Date().getFullYear());

          calendarDates[periodicityType] = dates.map(function(date) {
            var month = new String(date[0]),
                day = new String(date[1]);

            return year.leftPad(4, '0') + '-' + month.leftPad(2, '0') + '-' + day.leftPad(2, '0');
          });

          refreshCalendar();

          return this;
        },

      },

    },
  };
})();