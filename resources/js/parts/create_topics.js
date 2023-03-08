(function () {
    "use strict";

    if ($('#summernote').length) {
        $('#summernote').summernote({
            tabsize: 2,
            height: 400,
            placeholder: $('#summernote').attr('placeholder'),
            dialogsInBody: true,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
        });
    }

    $('body').on('click', '.panel-file-manager', function (e) {
        e.preventDefault();
        $(this).filemanager('file', {prefix: '/laravel-filemanager'})
    });

    $('body').on('click', '.add-btn', function (e) {
        let mainRow = $('.main-row');
        let icon = '<i class="fa fa-times"></i>';

        if (typeof feather !== "undefined") {
            icon = feather.icons['x'].toSvg({width: 18, height: 18});
        }

        let copy = mainRow.clone();
        copy.removeClass('main-row');
        copy.removeClass('d-none');

        const addBtn = copy.find('.add-btn');

        if (addBtn) {
            addBtn.removeClass('add-btn btn-primary')
                .addClass('btn-danger remove-btn')
                .html(icon);
        }

        var copyHtml = copy.prop('innerHTML');
        copyHtml = copyHtml.replaceAll('record', randomString());
        copyHtml = copyHtml.replaceAll('btn-primary', 'btn-danger');
        copyHtml = copyHtml.replaceAll('add-btn', 'remove-btn');

        copy.html(copyHtml);
        $('#topicImagesInputs').append(copy);
    });

    $('body').on('click', '.remove-btn', function (e) {
        e.preventDefault();
        $(this).closest('.input-group').remove();
    });
})(jQuery);
