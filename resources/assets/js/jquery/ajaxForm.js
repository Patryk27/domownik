(function($) {
  /**
   * Enables dynamic form validation, submitting etc. of given form.
   * @param {array} options
   * @return {jQuery}
   */
  $.fn.ajaxForm = function(options) {
    var form = $(this);

    options = $.extend({
      /**
       * Passes objectified form data and returns form data which should be sent to the server.
       * @param {Object} data
       * @returns {Object.<string, string>}
       */
      prepareData: function(data) {
        return data;
      },

      /**
       * Called just before submitting the form.
       * @param {Object} data
       * @returns {jQuery}
       */
      beforeSubmit: function(data) {
        return form;
      },

      /**
       * Called just after submitting form.
       * @returns {}
       */
      afterSubmit: function() {
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
        }
      },

      /**
       * Called after receiving an invalid response.
       * @param {Object} xhr
       * @param {String} textStatus
       * @param {String} errorThrown
       * @returns []
       */
      error: function(xhr, textStatus, errorThrown) {
        // dummy function
      },
    }, options);

    /**
     * Parses given error response, adding error text to given fields.
     * @param {Object.<string, string>} errorResponse
     * @returns {}
     */
    function parseErrorResponse(errorResponse) {
      $(form).form('clearErrors');

      for (var controlName in errorResponse) {
        if (!errorResponse.hasOwnProperty(controlName)) {
          continue;
        }

        $(form).form('addError', {
          controlName: controlName,
          message: errorResponse[controlName],
        });
      }
    };

    function submitForm() {
      var formId = $(form).prop('id');

      var formData = options.prepareData($(form).serializeObject());

      options.beforeSubmit(formData);

      // send the request
      console.log('Submitting form with id=\'{0}\'...'.format(formId));

      $.ajax({
        url: form.attr('action'),
        method: 'post',
        data: formData,
      }).done(function(msg) {
        console.log('... succeeded.');

        options.success(msg);
      }).fail(function(xhr, textStatus, errorThrown) {
        console.log('... failed.');
        console.log('... -> textStatus = {0}'.format(textStatus));
        console.log('... -> errorThrown = {0}'.format(errorThrown));

        // 422 Unprocessable Entity
        if (xhr.status === 422) {
          var response = JSON.parse(xhr.responseText);

          console.log('... -> error response:');
          console.log(response);

          parseErrorResponse(response);
        }

        options.error(xhr, textStatus, errorThrown);
      }).always(function() {
        options.afterSubmit();
      });
    };

    form.on('submit', function() {
      submitForm();
      return false;
    });

    return this;
  };
})(jQuery);