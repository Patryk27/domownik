(function ($) {
    $(document).on('click', '.btn-confirm', function () {
        var btn = $(this);

        function onConfirmed() {
            var url = btn.data('confirm-url');
            var method = btn.data('confirm-method');

            if (url === undefined) {
                console.error('data-confirm-url is undefined on ', btn, ' - giving up.');
                throw 'data-confirm-url is undefined.';
            }

            if (method === undefined) {
                console.warn('data-confirm-method is undefined on ', btn, ' - assuming \'get\'.');
                method = 'get';
            }

            console.log(url);
            console.log(method);

            $.ajax({
                url: url,
                method: method,
            }).done(function (msg) {
                if (typeof msg === 'object' && msg.hasOwnProperty('redirectUrl')) {
                    console.info('Redirecting to: ', msg.redirectUrl);
                    window.location.href = msg.redirectUrl;
                } else {
                    console.error('Don\'t know what to do with received response: ', msg);
                }
            });
        }

        bootbox.confirm(btn.data('confirm-message'), function (result) {
            if (!result) {
                return;
            }

            onConfirmed();
        });
    });
})(jQuery);