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
require('@fengyuanchen/datepicker');

(function($) {
    $(function() {
        var bootstrap = require('./app/bootstrap');
        bootstrap.bootstrapApplication();
    });
})(jQuery);

//noinspection JSUnusedLocalSymbols
var App = window.App;