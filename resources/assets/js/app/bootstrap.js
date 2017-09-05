module.exports = (function () {

    /**
     * @returns {}
     */
    function initializeTranslations() {
        var locale = window.App.Configuration.getLocale();

        console.log('-> application locale: ' + locale);
        App.Language.initialize(locale);

        // Moment.js
        moment.locale(locale);

        // Bootbox.js
        bootbox.setLocale(locale);
        bootbox.setDefaults({
            backdrop: true,
        });

        // Highcharts
        Highcharts.setOptions({
            lang: __('highcharts.language'),
        });

        // @fengyuanchen/datepicker
        $.fn.datepicker.languages['pl-PL'] = __('datepicker.language');

        $.fn.datepicker.setDefaults({
            autoHide: true,
            language: 'pl-PL',
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

        $(document).ajaxError(function (event, request, settings) {
            console.log('An ajax error has been caught:');

            console.log('-> event:');
            console.log(event);

            console.log('-> request:');
            console.log(request);

            console.log('-> settings:');
            console.log(settings);

            // do not show error on '422 Unprocessable Entity' because $.ajaxForm() already handles it
            if (request.status !== 422) {
                bootbox.alert(__('ajax.alerts.error'));
            }
        });
    }

    return {

        /**
         * @returns {}
         */
        bootstrapApplication: function () {
            console.log('Bootstrapping the application...');

            initializeTranslations();
            initializeAjax();

            console.log('-> bootstrapping done.');
        },

    };
})();