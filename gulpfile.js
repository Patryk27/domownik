process.env.DISABLE_NOTIFIER = true;

var Elixir = require('laravel-elixir');

var del = require('del');
var shell = require('gulp-shell');

function compileJs(mix) {
  mix.webpack('app.js');
}

function compileCss(mix) {
  //@formatter:off
  mix
    .sass('app.scss', './resources/assets/tmp/app.css')
    .styles([
      './resources/assets/tmp/app.css',
      './resources/assets/css/font-awesome.css',
      './node_modules/bootstrap-year-calendar/css/bootstrap-year-calendar.css',
    ], './public/css/styles.css')
    .copy('./node_modules/bootstrap/fonts/', './public/fonts/bootstrap/')
    .copy('./resources/assets/fonts/font-awesome/', './public/fonts/font-awesome/');
  //@formatter:on
}

Elixir(function(mix) {
  compileJs(mix);
  compileCss(mix);
});