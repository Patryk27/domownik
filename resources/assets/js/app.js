window.App = {
  Configuration: require('./app/configuration'),
  Language: require('./app/language'),
  Views: require('./app/views'),
};

require('./bootstrap');
require('./jquery');
require('./utils');

window.Highcharts = require('highcharts/highstock');

require('highcharts/modules/exporting')(window.Highcharts);

/**
 * Bootstraps the application.
 */
(function($) {
  console.log('Bootstrapping the application...');

  $(document).ready(function() {
    var locale = window.App.Configuration.getLocale();

    console.log('Application locale: ' + locale);

    moment.locale(locale);

    bootbox.setLocale(locale);
    bootbox.setDefaults({backdrop: true});

    // @todo
    /* Highcharts.setOptions({
     lang: {
     }
     }); */

    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': window.Laravel.csrfToken,
      },
    });

    $(document).ajaxError(function(event, request, settings) {
      console.log('An ajax error has been caught:');

      console.log('-> event:');
      console.log(event);

      console.log('-> request:');
      console.log(request);

      console.log('-> settings:');
      console.log(settings);

      bootbox.alert(__(':ajax.alerts.error'));
    });
  });
})(jQuery);

//noinspection JSUnusedLocalSymbols
var App = window.App;