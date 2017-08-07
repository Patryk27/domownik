(function($) {

    /**
     * Enables dynamic validation, submitting etc. on given form.
     * @param {array} options
     * @return {jQuery}
     */
    $.fn.ajaxForm = function(options) {
        var form = $(this);

        //noinspection JSUnusedGlobalSymbols
        var context = {

            /**
             * Returns form DOM element.
             * @returns {jQuery}
             */
            getForm: function() {
                return form;
            },

            /**
             * Returns default form options.
             */
            getDefaultOptions: function() {
                return {

                    /**
                     * Passes objectified form data and returns form data which should be sent to the server.
                     * @param {Object} data
                     * @returns {Object}
                     */
                    prepareData: function(data) {
                        return data;
                    },

                    /**
                     * Called just before submitting the form.
                     * @param {Object} data
                     * @returns {}
                     */
                    beforeSubmit: function(data) {
                        // dummy function
                    },

                    /**
                     * Called after receiving a successful response.
                     * @param {String|Object} msg
                     * @returns {}
                     */
                    success: function(msg) {
                        if (msg.hasOwnProperty('redirectUrl')) {
                            window.location.href = msg.redirectUrl;
                            return;
                        }

                        context.setFormEnabled(true);

                        if (msg.hasOwnProperty('success') && !msg.success) {
                            bootbox.alert(msg.message);
                        }
                    },

                    /**
                     * Called after receiving an invalid response.
                     * @param {Object} xhr
                     * @param {String} textStatus
                     * @param {String} errorThrown
                     * @returns {}
                     */
                    error: function(xhr, textStatus, errorThrown) {
                        // dummy function
                    },

                    /**
                     * @returns {}
                     */
                    always: function() {
                        // dummy function
                    },

                };
            },

            /**
             * Changes form state (enables/disables it).
             * @param {Boolean} enabled
             */
            setFormEnabled: function(enabled) {
                var submitBtn = $(form.find('button[type="submit"]'));

                if (enabled) {
                    form.form('enable');

                    if (submitBtn) {
                        var oldHtml = $(submitBtn).data('old-html');
                        submitBtn.html(oldHtml);
                    }
                } else {
                    form.form('disable');

                    if (submitBtn) {
                        submitBtn.
                            data('old-html', submitBtn.html()).
                            html('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    }
                }
            },

        };

        options = $.extend(context.getDefaultOptions(), options);

        /**
         * Parses given error response, adding error text to given fields.
         * @param {Object} errors
         * @returns {}
         */
        function parseErrorResponse(errors) {
            $(form).form('clearErrors');

            for (var controlName in errors) {
                if (!errors.hasOwnProperty(controlName)) {
                    continue;
                }

                $(form).form('addError', {
                    controlName: controlName,
                    message: errors[controlName],
                });
            }
        };

        /**
         * @returns {boolean}
         */
        function handleSubmit() {
            var formId = $(form).prop('id');
            var formData = options.prepareData($(form).serializeObject());

            options.beforeSubmit(formData);

            // send the request
            console.log('Submitting form with id=\'{0}\'...'.format(formId));

            context.setFormEnabled(false);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                data: formData,
            }).done(function(msg) {
                console.log('... succeeded.');

                options.success.call(context, msg);
            }).fail(function(xhr, textStatus, errorThrown) {
                console.error('... failed.');
                console.error('... -> xhr = ', xhr);
                console.error('... -> textStatus = ', textStatus);
                console.error('... -> errorThrown = ', errorThrown);

                // 422 Unprocessable Entity
                if (xhr.status === 422) {
                    var response = JSON.parse(xhr.responseText);
                    console.log('... -> error response = ', response);

                    parseErrorResponse(response);
                }

                options.error(xhr, textStatus, errorThrown);
                context.setFormEnabled(true);
            }).always(function() {
                options.always();
            });

            return false;
        };

        form.on('submit', handleSubmit);

        return this;
    };

    $(function() {
        $('.form-ajax').each(function() {
            $(this).ajaxForm();
        });
    });
})(jQuery);