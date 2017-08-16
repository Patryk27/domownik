process.env.DISABLE_NOTIFIER = true;

var elixir = require('laravel-elixir');
var del = require('del');
var shell = require('gulp-shell');

function compileApplication(mix) {
    mix.webpack('app.js');

    mix.sass('app.scss', './resources/assets/tmp/app.css');

    mix.styles([
        './resources/assets/tmp/app.css',
        './resources/assets/css/font-awesome.css',
        './node_modules/bootstrap-year-calendar/css/bootstrap-year-calendar.css',
    ], './public/css/app.css');

    mix.copy('./node_modules/bootstrap/fonts/', './public/fonts/bootstrap/');
    mix.copy('./resources/assets/fonts/font-awesome/', './public/fonts/font-awesome/');
}

function compileThirdParty(mix) {
    mix.scripts([
        './node_modules/@fengyuanchen/datepicker/dist/datepicker.min.js',
    ], './public/js/third-party.js');

    mix.styles([
        './node_modules/@fengyuanchen/datepicker/dist/datepicker.min.css',
    ], './public/css/third-party.css');
}

elixir(function(mix) {
    compileApplication(mix);
    compileThirdParty(mix);
});