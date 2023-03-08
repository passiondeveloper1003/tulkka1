(function () {
    "use strict";

    $('body').on('click', '.js-cookie-customize-settings', function (e) {
        e.preventDefault();

        const random = randomString();
        let clone = $('#cookieSecurityModal').clone();
        clone.removeClass('d-none');
        let cloneHtml = clone.prop('innerHTML');
        cloneHtml = cloneHtml.replaceAll('record', random);

        clone.html('<div id="cookieSecuritySwalModal">' + cloneHtml + '</div>');

        Swal.fire({
            html: clone,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '45rem',
        });
    });


    $('body').on('click', '.js-cookie-settings-modal-items-help', function (e) {
        e.preventDefault();

        const parent = $(this).closest('.cookie-settings-modal-items-card');

        const ulCard = parent.find('.cookie-settings-modal-items-card__description');
        const scrollHeight = ulCard[0].scrollHeight;

        const allItems = $('.cookie-settings-modal-items-card__description');
        allItems.css('height', '0');

        if (!ulCard.hasClass('active')) {
            ulCard.css('height', scrollHeight + 'px');
            ulCard.addClass('active');
        } else {
            ulCard.css('height', '0px');
            ulCard.removeClass('active');
        }
    });


    $('body').on('click', '.js-accept-all-cookies', function (e) {
        e.preventDefault();

        const $this = $(this);
        const action = '/cookie-security/all';
        const data = {};

        handleStoreCookieSecurity($this, action, data);
    });

    $('body').on('click', '.js-store-customize-cookies', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $('#cookieSecuritySwalModal .js-cookie-form-customize-inputs');
        const action = '/cookie-security/customize';
        const data = $form.serializeObject();

        handleStoreCookieSecurity($this, action, data);
    });

    function showErrorToast() {
        $.toast({
            heading: oopsLang,
            text: somethingWentWrongLang,
            bgColor: '#f63c3c',
            textColor: 'white',
            hideAfter: 10000,
            position: 'bottom-right',
            icon: 'error'
        });
    }

    function handleStoreCookieSecurity($this, action, data) {
        $this.addClass('loadingbar primary').prop('disabled', true);

        $.post(action, data, function (result) {
            $this.removeClass('loadingbar primary').prop('disabled', false);

            if (result && result.code === 200) {
                $.toast({
                    text: result.msg,
                    bgColor: '#43d477',
                    textColor: 'white',
                    hideAfter: 10000,
                    position: 'bottom-right',
                    icon: 'success'
                });

                Swal.close();

                $('.cookie-security-dialog').remove();
            } else {
                showErrorToast();
            }
        }).fail(err => {
            $this.removeClass('loadingbar primary').prop('disabled', false);

            showErrorToast();
        });
    }
})(jQuery);
