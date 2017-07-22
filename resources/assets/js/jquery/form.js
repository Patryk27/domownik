(function($) {
  $.fn.form = function(options, args) {
    var form = $(this);

    /**
     * Returns form group according to given control name.
     * @param {String} controlName
     * @returns {jQuery}
     */
    function getFormGroupByControlName(controlName) {
      return $('.form-group[data-control-name="{0}"]'.format(controlName));
    }

    /**
     * Adds an error message to given form's control.
     * @param {String} controlName
     * @param {String} errorMessage
     */
    function addError(controlName, errorMessage) {
      // look for the control's (input's, textarea's etc.) form-group
      formGroup = $('#' + controlName).parent('.form-group');

      if (formGroup.length === 0) {
        console.ERROR('Unable to find form-group block for element with controlName=\'{0}\'.'.format(controlName));
        return;
      }

      formGroup.addClass('has-error');

      // create a new help block or reuse the existing one
      var helpBlock = formGroup.find('.help-block');

      if (helpBlock.length === 0) {
        helpBlock = $('<p>');
        formGroup.append(helpBlock);
      }

      helpBlock.empty();

      var ul = $('<ul>').addClass('list-unstyled');
      var li = $('<li>').html(errorMessage);

      ul.append(li);

      helpBlock.addClass('help-block with-errors');
      helpBlock.append(ul);
    }

    /**
     * Removes every error from the form.
     */
    function clearErrors() {
      form.find('.form-group').removeClass('has-error');
      form.find('.help-block').html('');
    }

    function enable() {
      form.find('*').prop('disabled', false);
    }

    function disable() {
      form.find('*').prop('disabled', true);
    }

    // ----------------------- //

    this.addError = function(args) {
      addError(args.controlName, args.message);
    };

    this.clearErrors = function() {
      clearErrors();
    };

    this.enable = function() {
      enable();
    };

    this.disable = function() {
      disable();
    };

    if (typeof(this[options]) === 'function') {
      return this[options](args);
    } else {
      throw new Error('$.fn.form() -> unknown argument: {0}'.format(options));
    }
  };
})(jQuery);