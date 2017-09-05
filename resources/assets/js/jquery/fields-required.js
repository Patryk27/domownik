(function ($) {
    /**
     * Marks given element and all appropriate children as 'required' or 'not required', depending on the first
     * parameter. Takes care also for Bootstrap's "required" class.
     * @param {bool} isRequired
     * @returns {jQuery}
     */
    $.fn.fieldsRequired = function (isRequired) {
        var applyTo = function (element) {
            $(element).prop('required', isRequired);

            /**
             * We also need to add/remove the 'required' class from the control's wrapper (the .form-group), if one can
             * be found - otherwise no asterisk reminder will be shown in the control's label.
             */
            $(element).each(function () {
                var elementId = $(this).prop('id');
                var formGroup = $('.form-group[data-control-id="{0}"]'.format(elementId));

                if (formGroup.length > 0) {
                    if (isRequired) {
                        formGroup.addClass('required');
                    } else {
                        formGroup.removeClass('required');
                    }
                }
            });
        };

        const pattern = 'input, select, textarea';

        // apply to the control or control children, whichever is appropriate
        if (this.is(pattern)) {
            applyTo(this);
        } else {
            this.find(pattern).each(function () {
                applyTo(this);
            });
        }

        return this;
    };
})(jQuery);