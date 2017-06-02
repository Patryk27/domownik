window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
window.bootbox = require('bootbox');
window.jstree = require('jstree');
window.moment = require('moment');

require('bootstrap-validator');
require('bootstrap-sass');
require('bootstrap-year-calendar');

window.axios = require('axios');

window.axios.defaults.headers.common = {
  'X-CSRF-TOKEN': window.Laravel.csrfToken,
  'X-Requested-With': 'XMLHttpRequest',
};