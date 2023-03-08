(function ($) {
    "use strict";

    $('body').on('click', '.js-view-comment', function (e) {
        e.preventDefault();
        var $this = $(this);
        var comment_id = $this.attr('data-comment-id');
        var comment = $('#commentDescription' + comment_id).val();


        var html = '<div class="">' +
            '<h3 class="section-title after-line">' + commentLang + '</h3>' +
            '<p class="font-weight-500 text-gray mt-20">' + comment + '</p>' +
            '</div>';

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '40rem',
        });


    });

})(jQuery);
