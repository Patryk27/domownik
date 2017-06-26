module.exports = (function() {

  /**
   * @returns {}
   */
  function initializeTranslations() {
    var locale = window.App.Configuration.getLocale();

    console.log('-> application locale: ' + locale);
    App.Language.initialize(locale);

    // momentjs
    moment.locale(locale);

    // bootbox
    bootbox.setLocale(locale);
    bootbox.setDefaults({
      backdrop: true
    });

    // highcharts
    Highcharts.setOptions({
      lang: __(':highcharts.language'),
    });
  }

  /**
   * @returns {}
   */
  function initializeAjax() {
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

      // do not show error on '422 Unprocessable Entity' because $.ajaxForm() already handles it
      if (request.status !== 422) {
        bootbox.alert(__(':ajax.alerts.error'));
      }
    });
  }

  return {

    /**
     * @returns {}
     */
    bootstrapApplication: function() {
      console.log('Bootstrapping the application...');

      initializeTranslations();
      initializeAjax();

      console.log('-> bootstrapping done.');
    },

  };
})();