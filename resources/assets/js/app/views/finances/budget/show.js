module.exports = function() {
  /**
   * @type {?Number}
   */
  var budgetId = null;

  /**
   * @type {?Highcharts}
   */
  var chart = null;

  /**
   * @returns {}
   */
  function initializeView() {
    $('#budget-history-group-mode').on('change', function() {
      if (chart === null) {
        return;
      }

      var unitMap = {
        'daily': 'day',
        'weekly': 'week',
        'monthly': 'month',
        'yearly': 'year',
      };

      var unit = unitMap[$(this).val()];

      chart.series[0].update({
        dataGrouping: {
          approximation: 'sum',
          enabled: true,
          forced: true,
          units: [
            [unit, [1]],
          ],
        },
      });
    });

    $.ajax({
      url: '/finances/budget/get-history',

      data: {
        budgetId: budgetId,
        groupMode: $('#budget-history-group-mode').val(),
      },

      dataType: 'json',
    }).done(function(data) {
      var seriesData = [];

      $(data).each(function(idx, val) {
        var date = Date.UTC(val[0][0], val[0][1], val[0][2]),
            value = val[1];

        seriesData.push([
          date,
          value,
        ]);
      });

      chart = Highcharts.stockChart('budget-history', {
        chart: {
          zoomType: 'x',
        },

        title: {
          text: 'Historia budżetu', // @todo translation
        },

        xAxis: {
          type: 'datetime',
        },

        yAxis: {
          title: {
            text: 'Kwota', // @todo translation
          },
        },

        legend: {
          enabled: false,
        },

        plotOptions: {
          area: {
            fillColor: {
              linearGradient: {
                x1: 0,
                y1: 0,
                x2: 0,
                y2: 1,
              },

              stops: [
                [0, Highcharts.getOptions().colors[0]],
                [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')],
              ],
            },

            marker: {
              radius: 2,
            },

            lineWidth: 1,

            states: {
              hover: {
                lineWidth: 1,
              },
            },

            threshold: null,
          },
        },

        rangeSelector: {
          buttons: [
            {
              type: 'day',
              count: 3,
              text: '3d',
            },
            {
              type: 'week',
              count: 1,
              text: '1w',
            },
            {
              type: 'month',
              count: 1,
              text: '1m',
            },
            {
              type: 'month',
              count: 6,
              text: '6m',
            },
            {
              type: 'year',
              count: 1,
              text: '1y',
            },
            {
              type: 'all',
              text: 'All',
            },
          ],

          selected: 3,
        },

        series: [
          {
            type: 'area',
            name: 'Kwota', // @todo translation
            data: seriesData,
          },
        ],
      });
    });
  }

  return {

    /**
     * @param {{}} options
     * @return {}
     */
    initializeView: function(options) {
      budgetId = options.budgetId;

      $(function() {
        initializeView();
      });
    },

  };
}();