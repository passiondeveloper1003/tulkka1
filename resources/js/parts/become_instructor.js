(function ($) {
    "use strict";

    $('body').on('click', '.panel-file-manager', function (e) {
        e.preventDefault();
        $(this).filemanager('file', {prefix: '/laravel-filemanager'});
    });

    $('body').on('change', 'select[name="role"]', function (e) {
        e.preventDefault();

        const $instructorLabel = $('.js-instructor-label');
        const $organizationLabel = $('.js-organization-label');

        if ($(this).val() === 'teacher') {
            $organizationLabel.addClass('d-none');
            $instructorLabel.removeClass('d-none');
        } else {
            $organizationLabel.removeClass('d-none');
            $instructorLabel.addClass('d-none');
        }
    });

    $('body').on('change', 'input[name="id"]', function (e) {
        e.preventDefault();

        $('button#paymentSubmit').removeAttr('disabled');
    });
})(jQuery);
