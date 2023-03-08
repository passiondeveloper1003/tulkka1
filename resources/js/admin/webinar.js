(function ($) {
    "use strict";

    // form serialize to Object
    $.fn.serializeObject = function () {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name]) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    function randomString() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    /**
     * close swl
     * */
    $('body').on('click', '.close-swl', function (e) {
        e.preventDefault();
        Swal.close();
    });


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
            ['view', ['fullscreen', 'codeview']],
        ],
    });


    $('body').on('click', '#saveAndPublish', function (e) {
        e.preventDefault();
        $('#forDraft').val('publish');
        $('#webinarForm').trigger('submit');
    });

    $('body').on('click', '#saveAsDraft', function (e) {
        e.preventDefault();
        $('#forDraft').val(1);
        $('#webinarForm').trigger('submit');
    });

    $('body').on('click', '#saveReject', function (e) {
        e.preventDefault();
        $('#forDraft').val('reject');
        $('#webinarForm').trigger('submit');
    });

    $('#partnerInstructorSwitch').on('change.bootstrapSwitch', function (e) {
        let isChecked = e.target.checked;

        if (isChecked) {
            $('#partnerInstructorInput').removeClass('d-none');

            handleSearchableSelect2('js-search-partner-user', '/admin/users/search', 'name');
        } else {
            $('#partnerInstructorInput').addClass('d-none');
        }
    });

    $('body').on('change', '#categories', function (e) {
        e.preventDefault();
        let category_id = this.value;
        $.get('/admin/filters/get-by-category-id/' + category_id, function (result) {

            if (result && typeof result.filters !== "undefined" && result.filters.length) {
                let html = '';
                Object.keys(result.filters).forEach(key => {
                    let filter = result.filters[key];
                    let options = [];

                    if (filter.options.length) {
                        options = filter.options;
                    }

                    html += '<div class="col-12 col-md-3">\n' +
                        '<div class="webinar-category-filters">\n' +
                        '<strong class="category-filter-title d-block">' + filter.title + '</strong>\n' +
                        '<div class="py-10"></div>\n' +
                        '\n';

                    if (options.length) {
                        Object.keys(options).forEach(index => {
                            let option = options[index];

                            html += '<div class="form-group mt-20 d-flex align-items-center justify-content-between">\n' +
                                '<label class="" for="filterOption' + option.id + '">' + option.title + '</label>\n' +
                                '<div class="custom-control custom-checkbox">\n' +
                                '<input type="checkbox" name="filters[]" value="' + option.id + '" class="custom-control-input" id="filterOption' + option.id + '">\n' +
                                '<label class="custom-control-label" for="filterOption' + option.id + '"></label>\n' +
                                '</div>\n' +
                                '</div>\n';
                        })
                    }

                    html += '</div></div>';
                });

                $('#categoriesFiltersContainer').removeClass('d-none');
                $('#categoriesFiltersCard').html(html);
            } else {
                $('#categoriesFiltersContainer').addClass('d-none');
                $('#categoriesFiltersCard').html('');
            }
        })
    });

    /**
     * add ticket
     * */
    $('body').on('click', '#webinarAddTicket', function (e) {
        e.preventDefault();
        let add_ticket_modal = '<div id="addTicketModal">';
        add_ticket_modal += $('#webinarTicketModal').html();
        add_ticket_modal += '</div>';

        Swal.fire({
            html: add_ticket_modal,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
        });

        resetDatePickers();
    });

    $('body').on('click', '#saveTicket', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $('#addTicketModal .js-form');
        handleWebinarItemForm(form, $this);
    });

    /**
     * Contents
     * */

    $(document).ready(function () {

        const style = getComputedStyle(document.body);
        const primaryColor = style.getPropertyValue('--primary');

        function updateToDatabase(table, idString) {
            $.post('/admin/webinars/order-items', {table: table, items: idString}, function (result) {
                if (result && result.title && result.msg) {
                    $.toast({
                        heading: result.title,
                        text: result.msg,
                        bgColor: primaryColor,
                        textColor: 'white',
                        hideAfter: 10000,
                        position: 'bottom-right',
                        icon: 'success'
                    });
                }
            })
        }

        function setSortable(target) {
            if (target.length) {
                target.sortable({
                    group: 'no-drop',
                    handle: '.move-icon',
                    axis: "y",
                    update: function (e, ui) {
                        var sortData = target.sortable('toArray', {attribute: 'data-id'});
                        var table = e.target.getAttribute('data-order-table');

                        updateToDatabase(table, sortData.join(','))
                    }
                });
            }
        }

        const items = [];

        var draggableContentLists = $('.draggable-content-lists');
        if (draggableContentLists.length) {
            for (let item of draggableContentLists) {
                items.push($(item).attr('data-drag-class'))
            }
        }

        if (items.length) {
            for (let item of items) {
                const tag = $('.' + item);

                if (tag.length) {
                    setSortable(tag);
                }
            }
        }

        const $fileForms = $('.file-form');

        if ($fileForms && $fileForms.length) {
            $fileForms.each(key => {
                if ($fileForms[key]) {
                    const $form = $($fileForms[key]);

                    const source = $form.find('.js-file-storage').val();
                    const fileType = $form.find('.js-ajax-file_type').val();
                    handleShowFileInputsBySource($form, source, fileType);
                }
            });
        }

        if ($('.accordion-content-wrapper .attachments-select2').length) {
            $('.accordion-content-wrapper .attachments-select2').select2({
                multiple: true,
                width: '100%',
            });
        }

        var summernoteTarget = $('.accordion-content-wrapper .js-content-summernote');
        if (summernoteTarget.length) {
            summernoteTarget.summernote({
                tabsize: 2,
                height: 400,
                dialogsInBody: true,
                callbacks: {
                    onChange: function (contents, $editable) {
                        $('.js-hidden-content-summernote').val(contents);
                    }
                }
            });
        }
    });

    $('body').on('change', '.js-webinar-content-locale', function (e) {
        e.preventDefault();

        const $this = $(this);
        const $form = $(this).closest('.js-content-form');
        const locale = $this.val();
        const webinarId = $this.attr('data-webinar-id');
        const item_id = $this.attr('data-id');
        const relation = $this.attr('data-relation');
        let fields = $this.attr('data-fields');
        fields = fields.split(',');


        $this.addClass('loadingbar gray');

        const path = '/admin/webinars/' + webinarId + '/getContentItemByLocale';
        const data = {
            item_id,
            locale,
            relation
        };

        $.post(path, data, function (result) {
            if (result && result.item) {
                const item = result.item;

                Object.keys(item).forEach(function (key) {
                    const value = item[key];

                    if ($.inArray(key, fields) !== -1) {
                        let element = $form.find('.js-ajax-' + key);
                        element.val(value);
                    }

                    if (relation === 'textLessons' && key === 'content') {
                        var summernoteTarget = $form.find('.js-content-' + item_id);

                        if (summernoteTarget.length) {
                            summernoteTarget.summernote('destroy');


                            summernoteTarget.val(value);
                            $('.js-hidden-content-' + item_id).val(value);

                            summernoteTarget.summernote({
                                tabsize: 2,
                                height: 400,
                                dialogsInBody: true,
                                callbacks: {
                                    onChange: function (contents, $editable) {
                                        $('.js-hidden-content-' + item_id).val(contents);
                                    }
                                }
                            });
                        }
                    }
                });

                $this.removeClass('loadingbar gray');
            }
        }).fail(err => {
            $this.removeClass('loadingbar gray');
        });
    });

    function handleFileFormSubmit(form, $this) {
        let data = serializeObjectByTag(form);
        let action = form.attr('data-action');

        $this.addClass('loadingbar primary').prop('disabled', true);
        form.find('input').removeClass('is-invalid');
        form.find('textarea').removeClass('is-invalid');

        var formData = new FormData();

        const s3Input = form.find('.js-s3-file-input');

        if (s3Input && s3Input.prop('files') && s3Input.prop('files')[0]) {
            formData.append('s3_file', s3Input.prop('files')[0]);
        }

        const items = form.find('input, textarea, select').serializeArray();

        $.each(items, function () {
            formData.append(this.name, this.value);
        });

        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            success: function (result) {
                if (result && result.code === 200) {
                    //window.location.reload();
                    Swal.fire({
                        icon: 'success',
                        html: '<h3 class="font-20 text-center text-dark-blue py-25">' + saveSuccessLang + '</h3>',
                        showConfirmButton: false,
                        width: '25rem',
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            },
            error: function (err) {
                $this.removeClass('loadingbar primary').prop('disabled', false);
                var errors = err.responseJSON;

                if (errors && errors.errors) {
                    Object.keys(errors.errors).forEach((key) => {
                        const error = errors.errors[key];
                        let element = form.find('.js-ajax-' + key);

                        element.addClass('is-invalid');
                        element.parent().find('.invalid-feedback').text(error[0]);
                    });
                }
            }
        });
    }

    window.handleWebinarItemForm = function (form, $this) {
        let data = serializeObjectByTag(form);
        let action = form.attr('data-action');

        $this.addClass('loadingbar primary').prop('disabled', true);
        form.find('input').removeClass('is-invalid');
        form.find('textarea').removeClass('is-invalid');

        $.post(action, data, function (result) {
            if (result && result.code === 200) {
                //window.location.reload();
                Swal.fire({
                    icon: 'success',
                    html: '<h3 class="font-20 text-center text-dark-blue py-25">' + saveSuccessLang + '</h3>',
                    showConfirmButton: false,
                    width: '25rem',
                });

                setTimeout(() => {
                    window.location.reload();
                }, 500)
            }
        }).fail(err => {
            $this.removeClass('loadingbar primary').prop('disabled', false);
            var errors = err.responseJSON;

            if (errors && errors.status === 'zoom_jwt_token_invalid') {
                Swal.fire({
                    icon: 'error',
                    html: '<h3 class="font-20 text-center text-dark-blue py-25">' + zoomJwtTokenInvalid + '</h3>',
                    showConfirmButton: false,
                    width: '25rem',
                });
            }

            if (errors && errors.errors) {
                Object.keys(errors.errors).forEach((key) => {
                    const error = errors.errors[key];
                    let element = form.find('.js-ajax-' + key);

                    if (key === 'zoom-not-complete-alert') {
                        form.find('.js-zoom-not-complete-alert').removeClass('d-none');
                    } else {
                        element.addClass('is-invalid');
                        element.parent().find('.invalid-feedback').text(error[0]);
                    }
                });
            }
        })
    }

    $('body').on('click', '.save-chapter', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.chapter-form');

        handleWebinarItemForm(form, $this);
    });


    $('body').on('click', '.js-add-chapter', function (e) {
        const $this = $(this);

        const webinarId = $this.attr('data-webinar-id');
        const type = $this.attr('data-type');
        const itemId = $this.attr('data-chapter');
        const locale = $this.attr('data-locale');

        const random = itemId ? itemId : randomString();

        var clone = $('#chapterModalHtml').clone();
        clone.removeClass('d-none');
        var cloneHtml = clone.prop('innerHTML');
        cloneHtml = cloneHtml.replaceAll('record', random);

        clone.html('<div id="chapterModal' + random + '">' + cloneHtml + '</div>');

        Swal.fire({
            html: clone,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '36rem',
            onOpen: function () {

                const modal = $('#chapterModal' + random);

                modal.find('input.js-chapter-webinar-id').val(webinarId);
                modal.find('input.js-chapter-type').val(type);

                if (itemId) {
                    modal.find('.section-title').text(editChapterLang);

                    const path = '/admin/chapters/' + itemId + '/update';
                    modal.find('.chapter-form').attr('data-action', path);

                    $.get('/admin/chapters/' + itemId + '?locale=' + locale, function (result) {
                        if (result && result.chapter) {
                            modal.find('.js-ajax-title').val(result.chapter.title);

                            const status = modal.find('.js-chapter-status-switch');
                            if (result.chapter.status === 'active') {
                                status.prop('checked', true);
                            } else {
                                status.prop('checked', false);
                            }

                            const checkedAllContents = (result.chapter.check_all_contents_pass && result.chapter.check_all_contents_pass !== "0");
                            modal.find('.js-chapter-check-all-contents-pass').prop('checked', checkedAllContents);

                            var localeSelect = modal.find('.js-chapter-locale');
                            localeSelect.val(locale);
                            localeSelect.addClass('js-webinar-content-locale');
                            localeSelect.attr('data-id', itemId);
                        }
                    })
                }
            }
        });
    });

    $('body').on('click', '.js-add-course-content-btn, .add-new-interactive-file-btn', function (e) {
        e.preventDefault();
        const $this = $(this);
        const type = $this.attr('data-type');
        const chapterId = $this.attr('data-chapter');

        const contentTagId = '#chapterContentAccordion' + chapterId;
        const key = randomString();
        var html = '';

        switch (type) {
            case 'file':
                const newFileForm = $('#newFileForm');
                newFileForm.find('.chapter-input').val(chapterId);
                html = newFileForm.html();

                html = html.replace(/record/g, key);

                $(contentTagId).prepend(html);

                break;
            case 'new_interactive_file':
                const newInteractiveFileForm = $('#newInteractiveFileForm');
                newInteractiveFileForm.find('.chapter-input').val(chapterId);
                html = newInteractiveFileForm.html();

                html = html.replace(/record/g, key);

                $(contentTagId).prepend(html);

                break;
            case 'session':
                const newSessionForm = $('#newSessionForm');
                newSessionForm.find('.chapter-input').val(chapterId);
                html = newSessionForm.html();

                html = html.replace(/record/g, key);

                $(contentTagId).prepend(html);
                break;
            case 'text_lesson':
                const newTextLessonForm = $('#newTextLessonForm');
                newTextLessonForm.find('.chapter-input').val(chapterId);
                html = newTextLessonForm.html();

                html = html.replace(/record/g, key);

                html = html.replaceAll('attachments-select2', 'attachments-select2-' + key);
                html = html.replaceAll('js-content-summernote', 'js-content-summernote-' + key);
                html = html.replaceAll('js-hidden-content-summernote', 'js-hidden-content-summernote-' + key);

                $(contentTagId).prepend(html);

                $('.attachments-select2-' + key).select2({
                    multiple: true,
                    width: '100%',
                });

                $('.js-content-summernote-' + key).summernote({
                    tabsize: 2,
                    height: 400,
                    dialogsInBody: true,
                    callbacks: {
                        onChange: function (contents, $editable) {
                            $('.js-hidden-content-summernote-' + key).val(contents);
                        }
                    }
                });

                break;

            case 'assignment':
                const newAssignmentForm = $('#newAssignmentForm');
                newAssignmentForm.find('.chapter-input').val(chapterId);
                html = newAssignmentForm.html();

                html = html.replace(/record/g, key);

                $(contentTagId).prepend(html);
                break;

            case 'quiz':
                const newQuizForm = $('#newQuizForm');
                newQuizForm.find('.chapter-input').val(chapterId);
                html = newQuizForm.html();

                html = html.replace(/record/g, key);

                $(contentTagId).prepend(html);
                break;
        }

        resetDatePickers();
        feather.replace();
    });

    $('body').on('click', '.js-change-content-chapter', function (e) {
        e.preventDefault();
        const $this = $(this);
        const itemId = $this.attr('data-item-id');
        const itemType = $this.attr('data-item-type');
        const chapterId = $this.attr('data-chapter-id');

        const random = randomString();

        var clone = $('#changeChapterModalHtml').clone();
        clone.removeClass('d-none');
        var cloneHtml = clone.prop('innerHTML');

        clone.html('<div id="changeChapterModalHtml' + random + '">' + cloneHtml + '</div>');

        Swal.fire({
            html: clone,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '36rem',
            onOpen: function () {

                const modal = $('#changeChapterModalHtml' + random);

                modal.find('input.js-item-id').val(itemId);
                modal.find('input.js-item-type').val(itemType);
                modal.find('.js-ajax-chapter_id').val(chapterId).change();
            }
        });
    });

    $('body').on('click', '.save-change-chapter', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.change-chapter-form');

        handleWebinarItemForm(form, $this);
    });

    // ======
    // contents files
    function handleShowFileInputsBySource($form, source, fileType) {
        const featherIconsConf = {width: 20, height: 20};
        let icon = feather.icons['upload'].toSvg(featherIconsConf);

        const $fileTypeVolumeInputs = $form.find('.js-file-type-volume');
        const $volumeInputs = $form.find('.js-file-volume-field');
        const $downloadableInput = $form.find('.js-downloadable-input');
        const $onlineViewerInput = $form.find('.js-online_viewer-input');

        const $filePathInputGroup = $form.find('.js-file-path-input');
        const $s3FilePathInputGroup = $form.find('.js-s3-file-path-input');
        const $filePathButton = $form.find('.js-file-path-input button');
        const $filePathInput = $form.find('.js-file-path-input input');

        $filePathButton.addClass('panel-file-manager');
        $filePathInputGroup.removeClass('d-none');
        $s3FilePathInputGroup.addClass('d-none');

        switch (source) {
            case 'youtube':
            case 'vimeo':
            case 'iframe':
                $fileTypeVolumeInputs.addClass('d-none');
                $downloadableInput.find('input').prop('checked', false);
                $downloadableInput.addClass('d-none');

                $onlineViewerInput.find('input').prop('checked', false);
                $onlineViewerInput.addClass('d-none');

                icon = feather.icons['link'].toSvg(featherIconsConf);
                $filePathButton.removeClass('panel-file-manager');

                break;

            case 'external_link':
            case 's3':
                $fileTypeVolumeInputs.removeClass('d-none');

                if (fileType && fileType === 'video') {
                    $downloadableInput.removeClass('d-none');
                } else {
                    $downloadableInput.find('input').prop('checked', false);
                    $downloadableInput.addClass('d-none');
                }

                if (source === 'external_link') {
                    icon = feather.icons['external-link'].toSvg(featherIconsConf);
                    $filePathButton.removeClass('panel-file-manager');
                } else if (source === 's3') {
                    $filePathInputGroup.addClass('d-none');
                    $s3FilePathInputGroup.removeClass('d-none');
                }

                if (fileType && (fileType === 'pdf')) {
                    $onlineViewerInput.removeClass('d-none');
                } else {
                    $onlineViewerInput.find('input').prop('checked', false);
                    $onlineViewerInput.addClass('d-none');
                }

                break;

            case 'google_drive':
                $fileTypeVolumeInputs.removeClass('d-none');
                $downloadableInput.find('input').prop('checked', false);
                $downloadableInput.addClass('d-none');

                if (fileType && (fileType === 'pdf')) {
                    $onlineViewerInput.removeClass('d-none');
                } else {
                    $onlineViewerInput.find('input').prop('checked', false);
                    $onlineViewerInput.addClass('d-none');
                }

                icon = feather.icons['box'].toSvg(featherIconsConf);
                $filePathButton.removeClass('panel-file-manager');

                break;

            case 'upload':
                $fileTypeVolumeInputs.removeClass('d-none');
                $volumeInputs.addClass('d-none');
                $downloadableInput.removeClass('d-none');

                if (fileType && (fileType === 'pdf')) {
                    $onlineViewerInput.removeClass('d-none');
                } else {
                    $onlineViewerInput.find('input').prop('checked', false);
                    $onlineViewerInput.addClass('d-none');
                }
        }

        if (icon) {
            $filePathButton.html(icon);
        }

        if (filePathPlaceHolderBySource) {
            $filePathInput.attr('placeholder', filePathPlaceHolderBySource[source]);
        }

    }

    $('body').on('click', '.js-save-file', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.file-form');

        handleFileFormSubmit(form, $this);
    });

    $('body').on('change', '.js-file-storage', function (e) {
        e.preventDefault();

        const value = this.value;
        const formGroup = $(this).closest('form');
        const fileType = formGroup.find('.js-ajax-file_type').val();

        handleShowFileInputsBySource(formGroup, value, fileType);
    });

    $('body').on('change', '.js-ajax-file_type', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const formGroup = $(this).closest('form');
        const source = formGroup.find('.js-file-storage').val();

        handleShowFileInputsBySource(formGroup, source, value);
    });

    // Sessions
    $('body').on('change', '.js-api-input', function (e) {
        e.preventDefault();

        const sessionForm = $(this).closest('.session-form');
        const value = this.value;

        sessionForm.find('.js-zoom-not-complete-alert').addClass('d-none');
        sessionForm.find('.js-agora-chat-and-rec').addClass('d-none');

        if (value === 'big_blue_button') {
            sessionForm.find('.js-local-link').addClass('d-none');
            sessionForm.find('.js-api-secret').removeClass('d-none');
            sessionForm.find('.js-moderator-secret').removeClass('d-none');
        } else if (value === 'zoom') {
            sessionForm.find('.js-local-link').addClass('d-none');
            sessionForm.find('.js-api-secret').addClass('d-none');
            sessionForm.find('.js-moderator-secret').addClass('d-none');

            if (hasZoomApiToken && hasZoomApiToken !== 'true') {
                sessionForm.find('.js-zoom-not-complete-alert').removeClass('d-none');
            }
        } else if (value === 'agora') {
            sessionForm.find('.js-agora-chat-and-rec').removeClass('d-none');
            sessionForm.find('.js-api-secret').addClass('d-none');
            sessionForm.find('.js-local-link').addClass('d-none');
            sessionForm.find('.js-moderator-secret').addClass('d-none');
        } else {
            sessionForm.find('.js-local-link').removeClass('d-none');
            sessionForm.find('.js-api-secret').removeClass('d-none');
            sessionForm.find('.js-moderator-secret').addClass('d-none');
        }
    });

    $('body').on('click', '.js-save-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.session-form');

        handleWebinarItemForm(form, $this);
    });

    $('body').on('click', '.js-session-has-ended', function () {

        $.toast({
            heading: requestFailedLang,
            text: thisLiveHasEndedLang,
            bgColor: '#f63c3c',
            textColor: 'white',
            hideAfter: 10000,
            position: 'bottom-right',
            icon: 'error'
        });
    });

    // Text lession
    $('body').on('click', '.js-save-text_lesson', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.text_lesson-form');

        handleWebinarItemForm(form, $this);
    });

    // assignments

    $('body').on('click', '.js-save-assignment', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.assignment-form');

        handleWebinarItemForm(form, $this);
    });

    $('body').on('click', '.assignment-attachments-add-btn', function (e) {
        var $container = $(this).closest('.js-assignment-attachments-items');
        var mainRow = $container.find('.assignment-attachments-main-row');

        var copy = mainRow.clone();
        copy.removeClass('assignment-attachments-main-row');
        copy.removeClass('d-none');

        const removeBtn = copy.find('.assignment-attachments-remove-btn');

        if (removeBtn) {
            removeBtn.removeClass('d-none');
        }

        var copyHtml = copy.prop('innerHTML');
        copyHtml = copyHtml.replaceAll('assignmentTemp', randomString());
        copyHtml = copyHtml.replaceAll('btn-primary', 'btn-danger');
        copyHtml = copyHtml.replaceAll('assignment-attachments-add-btn', 'assignment-attachments-remove-btn');

        copy.html(copyHtml);
        $container.append(copy);
    });

    $('body').on('click', '.assignment-attachments-remove-btn', function (e) {
        e.preventDefault();
        $(this).closest('.js-ajax-attachments').remove();
    });

    /*
    * ./ Contents
    * */

    $('body').on('click', '.cancel-accordion', function (e) {
        e.preventDefault();

        $(this).closest('.accordion-row').remove();
    });


    /**
     * add webinar prerequisites
     * */
    $('body').on('click', '#webinarAddPrerequisites', function (e) {
        e.preventDefault();
        let add_prerequisites_modal = '<div id="addPrerequisitesModal">';
        add_prerequisites_modal += $('#webinarPrerequisitesModal').html();
        add_prerequisites_modal += '</div>';
        add_prerequisites_modal = add_prerequisites_modal.replaceAll('prerequisites-select', 'prerequisites-select2');
        add_prerequisites_modal = add_prerequisites_modal.replaceAll('str_', '');

        Swal.fire({
            html: add_prerequisites_modal,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
            onOpen: () => {
                $('.prerequisites-select2').select2({
                    placeholder: $(this).data('placeholder'),
                    minimumInputLength: 3,
                    allowClear: true,
                    ajax: {
                        url: '/admin/webinars/search',
                        dataType: 'json',
                        type: "POST",
                        quietMillis: 50,
                        data: function (params) {
                            var queryParameters = {
                                term: params.term,
                                webinar_id: $(this).data('webinar-id')
                            }
                            return queryParameters;
                        },
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.title,
                                        id: item.id
                                    }
                                })
                            };
                        }
                    }
                });
            },
        });
    });

    $('body').on('click', '#savePrerequisites', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $('#addPrerequisitesModal .js-prerequisites-form');
        handleWebinarItemForm(form, $this);
    });

    /**
     * add webinar FAQ
     * */
    $('body').on('click', '#webinarAddFAQ', function (e) {
        e.preventDefault();
        let add_faq_modal = '<div id="addFAQsModal">';
        add_faq_modal += $('#webinarFaqModal').html();
        add_faq_modal += '</div>';

        Swal.fire({
            html: add_faq_modal,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
        });
    });

    $('body').on('click', '#saveFAQ', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $('#addFAQsModal .js-faq-form');
        handleWebinarItemForm(form, $this);
    });

    /*
    * add extra description
    * */
    $('body').on('click', '#add_new_learning_materials', function (e) {
        e.preventDefault();
        const key = randomString();

        let html = '<div id="extraDescriptionModal">';
        html += $('#extraDescriptionForm').html();
        html += '</div>';

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
            onOpen: function () {
                $('#extraDescriptionModal input[name="type"]').val('learning_materials')
            }
        });
    });

    function handleCompanyLogosInputHtml(key) {
        let html = '<div id="extraDescriptionModal">';
        html += $('#extraDescriptionForm').html();
        html += '</div>';

        var modalHtml = $(html);
        modalHtml.find('.js-form-groups').children().remove();
        modalHtml.find('.js-form-groups').append('<div class="form-group">\n' +
            '            <label class="input-label">image</label>\n' +
            '            <div class="input-group">\n' +
            '                <div class="input-group-prepend">\n' +
            '                    <button type="button" class="input-group-text admin-file-manager" data-input="image_' + key + '" data-preview="holder">\n' +
            '                        <i class="fa fa-upload"></i>\n' +
            '                    </button>\n' +
            '                </div>\n' +
            '                <input type="text" name="value" id="image_' + key + '" class="form-control"/>\n' +
            '            </div>\n' +
            '        </div>');

        var mainHtml = '<div id="extraDescriptionModal">';
        mainHtml += modalHtml.html();
        mainHtml += '</div>';

        return mainHtml;
    }

    $('body').on('click', '#add_new_company_logos', function (e) {
        e.preventDefault();
        const key = randomString();
        var html = handleCompanyLogosInputHtml(key)

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
            onOpen: function () {
                $('#extraDescriptionModal input[name="type"]').val('company_logos')
            }
        });
    });

    $('body').on('click', '#add_new_requirements', function (e) {
        e.preventDefault();
        const key = randomString();

        let html = '<div id="extraDescriptionModal">';
        html += $('#extraDescriptionForm').html();
        html += '</div>';

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
            onOpen: function () {
                $('#extraDescriptionModal input[name="type"]').val('requirements')
            }
        });
    });

    $('body').on('click', '#saveExtraDescription', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $('#extraDescriptionModal .js-form');
        handleWebinarItemForm(form, $this);
    });

    $('body').on('click', '.edit-extraDescription', function (e) {
        e.preventDefault();
        const $this = $(this);

        editExtraDescription($this);
    });

    $('body').on('change', '.js-edit-extraDescription-locale-ajax', function (e) {
        e.preventDefault();
        const $this = $(this);
        const locale = $this.val();

        editExtraDescription($this, locale);
    });

    function editExtraDescription($this, locale) {
        const item_id = $this.attr('data-item-id');
        const webinar_id = $this.attr('data-webinar-id');

        const rendomKey = randomString();

        const edit_data = {
            item_id: webinar_id,
            locale: locale
        };

        $.post('/admin/webinar-extra-description/' + item_id + '/edit', edit_data, function (result) {
            if (result && result.webinarExtraDescription) {
                const webinarExtraDescription = result.webinarExtraDescription;

                let html = '<div id="extraDescriptionModal">';
                html += $('#extraDescriptionForm').html();
                html += '</div>';

                if (webinarExtraDescription.type === 'company_logos') {
                    html = handleCompanyLogosInputHtml(rendomKey);
                }

                html = html.replaceAll('/admin/webinar-extra-description/store', '/admin/webinar-extra-description/' + item_id + '/update');

                Swal.fire({
                    html: html,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '48rem',
                    onOpen: () => {
                        var $modal = $('#extraDescriptionModal');

                        Object.keys(webinarExtraDescription).forEach(key => {
                            $modal.find('[name="' + key + '"]').val(webinarExtraDescription[key]);
                        });

                        var localeSelect = $modal.find('select[name="locale"]');

                        if (localeSelect) {
                            localeSelect.addClass('js-edit-extraDescription-locale-ajax');
                            localeSelect.attr('data-item-id', item_id);
                            localeSelect.attr('data-webinar-id', webinar_id);
                        }
                    }
                });
            }
        });
    }

    /**
     * add webinar Quiz
     * */
    $('body').on('click', '#webinarAddQuiz', function (e) {
        e.preventDefault();
        let add_quiz_modal = '<div id="addQuizModal">';
        add_quiz_modal += $('#quizzesModal').html();
        add_quiz_modal += '</div>';
        add_quiz_modal = add_quiz_modal.replaceAll('quiz-select2', 'quiz-select22');

        Swal.fire({
            html: add_quiz_modal,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '30rem',
            onOpen: () => {
                $(".quiz-select22").select2({
                    placeholder: $(this).data('placeholder'),
                    allowClear: true,
                    width: '100%',
                });
            }
        });
    });

    $('body').on('click', '#saveQuiz', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $('#addQuizModal .js-form');
        handleWebinarItemForm(form, $this);
    });

    /*
    * edit ticket
    * */

    function editTicket($this, locale = null) {
        const ticket_id = $this.attr('data-ticket-id');
        const webinar_id = $this.attr('data-webinar-id');

        const edit_data = {
            item_id: webinar_id,
            locale: locale,
        };

        $.post('/admin/tickets/' + ticket_id + '/edit', edit_data, function (result) {
            if (result && result.ticket) {
                const ticket = result.ticket;

                let edit_ticket_modal = '<div id="addTicketModal">';
                edit_ticket_modal += $('#webinarTicketModal').html();
                edit_ticket_modal += '</div>';
                edit_ticket_modal = edit_ticket_modal.replaceAll('/admin/tickets/store', '/admin/tickets/' + ticket_id + '/update');

                Swal.fire({
                    html: edit_ticket_modal,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '48rem',
                    onOpen: () => {
                        $('.date-range-picker').daterangepicker({
                            locale: {format: 'YYYY-MM-DD'},
                            drops: 'down',
                            opens: 'right',
                            startDate: moment(ticket.start_date * 1000).toDate(),
                            endDate: moment(ticket.end_date * 1000).toDate(),
                        });
                        delete ticket.start_date;
                        delete ticket.end_date;

                        Object.keys(ticket).forEach(key => {
                            $('#addTicketModal').find('[name="' + key + '"]').val(ticket[key]);
                        });

                        var localeSelect = $('#addTicketModal').find('select[name="locale"]');

                        if (localeSelect) {
                            localeSelect.addClass('js-edit-ticket-locale-ajax');
                            localeSelect.attr('data-ticket-id', ticket_id);
                            localeSelect.attr('data-webinar-id', webinar_id);
                        }
                    }
                });
            }
        });
    }

    $('body').on('click', '.edit-ticket', function (e) {
        e.preventDefault();
        const $this = $(this);

        loadingSwl();

        editTicket($this);
    });

    $('body').on('change', '.js-edit-ticket-locale-ajax', function (e) {
        e.preventDefault();
        const $this = $(this);

        const locale = $this.val();

        editTicket($this, locale);
    });

    /*
    * edit session
    * */

    function editChapter($this, locale = null) {
        const chapter_id = $this.attr('data-chapter-id');
        const webinar_id = $this.attr('data-webinar-id');

        const edit_data = {
            item_id: webinar_id,
            locale: locale,
        };

        $.post('/admin/chapters/' + chapter_id + '/edit', edit_data, function (result) {
            if (result && result.chapter) {
                const chapter = result.chapter;

                let html = '<div id="editChapterModal">';
                html += $('#webinarChapterModal').html();
                html += '</div>';

                html = html.replaceAll('/admin/chapters/store', '/admin/chapters/' + chapter_id + '/update');
                const nameId = randomString();
                html = html.replaceAll('record', nameId);

                Swal.fire({
                    html: html,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '48rem',
                    onOpen: () => {
                        var $modal = $('#editChapterModal');

                        Object.keys(chapter).forEach(key => {
                            if (key === 'status') {
                                const checked = (chapter.status === 'active');

                                $modal.find('[name="' + key + '"]').prop('checked', checked)
                            } else if (key === 'check_all_contents_pass') {
                                const checkedAllContents = (chapter.check_all_contents_pass && chapter.check_all_contents_pass !== "0");
                                $modal.find('[name="' + key + '"]').prop('checked', checkedAllContents)
                            } else {
                                $modal.find('[name="' + key + '"]').val(chapter[key]);
                            }
                        });

                        var localeSelect = $modal.find('select[name="locale"]');

                        if (localeSelect) {
                            localeSelect.addClass('js-edit-chapter-locale-ajax');
                            localeSelect.attr('data-chapter-id', chapter_id);
                            localeSelect.attr('data-webinar-id', webinar_id);
                        }
                    }
                });
            }
        });
    }

    $('body').on('click', '.edit-chapter', function (e) {
        e.preventDefault();
        const $this = $(this);

        loadingSwl();

        editChapter($this);
    });

    $('body').on('change', '.js-edit-chapter-locale-ajax', function (e) {
        e.preventDefault();
        const $this = $(this);
        const locale = $this.val();

        editChapter($this, locale);
    });

    /*
    * edit session
    * */

    function editSession($this, locale = null) {
        const session_id = $this.attr('data-session-id');
        const webinar_id = $this.attr('data-webinar-id');

        const edit_data = {
            item_id: webinar_id,
            locale: locale
        };

        $.post('/admin/sessions/' + session_id + '/edit', edit_data, function (result) {
            if (result && result.session) {
                const session = result.session;

                let edit_session_modal = '<div id="addSessionModal">';
                edit_session_modal += $('#webinarSessionModal').html();
                edit_session_modal += '</div>';
                edit_session_modal = edit_session_modal.replaceAll('/admin/sessions/store', '/admin/sessions/' + session_id + '/update');
                const nameId = randomString();
                edit_session_modal = edit_session_modal.replaceAll('record', nameId);

                Swal.fire({
                    html: edit_session_modal,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '48rem',
                    onOpen: () => {
                        var $modal = $('#addSessionModal');

                        var datetimepicker = $('.datetimepicker');
                        datetimepicker.val(session.date);
                        datetimepicker.daterangepicker({
                            locale: {format: 'YYYY-MM-DD HH:mm'},
                            singleDatePicker: true,
                            timePicker: true,
                            timePicker24Hour: true,
                        });
                        delete session.date;

                        Object.keys(session).forEach(key => {
                            if (key === 'session_api') {
                                var apiInput = $modal.find('.js-api-input[value="' + session[key] + '"]');
                                apiInput.prop('checked', true);

                                $modal.find('.js-api-input').prop('disabled', true);

                                if (session[key] !== 'local') {
                                    $modal.find('.js-ajax-api_secret').prop('disabled', true);
                                    $modal.find('.js-ajax-date').prop('disabled', true);
                                    $modal.find('.js-ajax-duration').prop('disabled', true);
                                    $modal.find('.js-ajax-link').prop('disabled', true);
                                }

                                if (session[key] === 'big_blue_button') {
                                    $modal.find('.js-moderator-secret').removeClass('d-none');
                                    $modal.find('.js-ajax-moderator_secret').prop('disabled', true);
                                } else if (session[key] === 'zoom') {
                                    $modal.find('.js-local-link').addClass('d-none');
                                    $modal.find('.js-api-secret').addClass('d-none');
                                    $modal.find('.js-moderator-secret').addClass('d-none');
                                } else if (session[key] === 'agora') {
                                    $modal.find('.js-agora-chat-and-rec').removeClass('d-none');
                                    $modal.find('.js-api-secret').addClass('d-none');
                                    $modal.find('.js-local-link').addClass('d-none');
                                    $modal.find('.js-moderator-secret').addClass('d-none');
                                }

                            } else if (key === 'status') {
                                const checked = (session.status === 'active');

                                $modal.find('[name="' + key + '"]').prop('checked', checked);
                            } else if (key === 'check_previous_parts' || key === 'access_after_day') {
                                const sequenceContentSwitchChecked = (session.check_previous_parts || session.access_after_day !== null);

                                if (sequenceContentSwitchChecked) {
                                    $modal.find('.js-sequence-content-switch').prop('checked', true);
                                    $modal.find('[name="check_previous_parts"]').prop('checked', session.check_previous_parts);
                                    $modal.find('[name="access_after_day"]').val(session.access_after_day);
                                    $modal.find('.js-sequence-content-inputs').removeClass('d-none')
                                }
                            } else if (key === 'agora_settings') {
                                const agora_settings = JSON.parse(session.agora_settings);

                                if (agora_settings && agora_settings['chat'] && (agora_settings['chat'] === true || agora_settings['chat'] === 'true')) {
                                    $modal.find('[name="agora_chat"]').prop('checked', true)
                                }

                                if (agora_settings && agora_settings['record'] && (agora_settings['record'] === true || agora_settings['record'] === 'true')) {
                                    $modal.find('[name="agora_record"]').prop('checked', true)
                                }
                            } else {
                                $modal.find('[name="' + key + '"]').val(session[key]);
                            }
                        });

                        var localeSelect = $modal.find('select[name="locale"]');

                        if (localeSelect) {
                            localeSelect.addClass('js-edit-session-locale-ajax');
                            localeSelect.attr('data-session-id', session_id);
                            localeSelect.attr('data-webinar-id', webinar_id);
                        }
                    }
                });
            }
        });
    }

    $('body').on('click', '.edit-session', function (e) {
        e.preventDefault();
        const $this = $(this);

        loadingSwl();

        editSession($this);
    });

    $('body').on('change', '.js-edit-session-locale-ajax', function (e) {
        e.preventDefault();
        const $this = $(this);
        const locale = $this.val();

        editSession($this, locale);
    });


    $('body').on('change', '.js-video-demo-source', function (e) {
        e.preventDefault();

        const value = $(this).val();

        const $filePathUploadButton = $('.js-video-demo-path-input .js-video-demo-path-upload');
        const $filePathLinkButton = $('.js-video-demo-path-input .js-video-demo-path-links');
        const $filePathInput = $('.js-video-demo-path-input input');

        $filePathUploadButton.addClass('d-none');
        $filePathLinkButton.addClass('d-none');

        if (value === 'upload') {
            $filePathUploadButton.removeClass('d-none');
        } else {
            $filePathLinkButton.removeClass('d-none');
        }

        if (videoDemoPathPlaceHolderBySource) {
            $filePathInput.attr('placeholder', videoDemoPathPlaceHolderBySource[value]);
        }
    });

    /*
    * edit prerequisites
    * */
    $('body').on('click', '.edit-prerequisite', function (e) {
        e.preventDefault();
        const $this = $(this);
        const prerequisite_id = $this.attr('data-prerequisite-id');
        const webinar_id = $this.attr('data-webinar-id');

        loadingSwl();

        const edit_data = {
            item_id: webinar_id
        };

        $.post('/admin/prerequisites/' + prerequisite_id + '/edit', edit_data, function (result) {
            if (result && result.prerequisite) {
                const prerequisite = result.prerequisite;

                let edit_prerequisite_modal = '<div id="addPrerequisitesModal">';
                edit_prerequisite_modal += $('#webinarPrerequisitesModal').html();
                edit_prerequisite_modal += '</div>';
                edit_prerequisite_modal = edit_prerequisite_modal.replaceAll('prerequisites-select', 'prerequisites-select2');
                edit_prerequisite_modal = edit_prerequisite_modal.replaceAll('/admin/prerequisites/store', '/admin/prerequisites/' + prerequisite_id + '/update');
                edit_prerequisite_modal = edit_prerequisite_modal.replaceAll('str_', '');

                Swal.fire({
                    html: edit_prerequisite_modal,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '48rem',
                    onOpen: () => {
                        $('.prerequisites-select2').append('<option selected="selected" value="' + prerequisite.webinar_id + '">' + prerequisite.webinar_title + '</option>');

                        if (prerequisite.required === 1) {
                            $('#addPrerequisitesModal').find('[name="required"]').prop('checked', true);
                        }

                        $('.prerequisites-select2').select2({
                            placeholder: $(this).data('placeholder'),
                            minimumInputLength: 3,
                            allowClear: true,
                            ajax: {
                                url: '/admin/webinars/search',
                                dataType: 'json',
                                type: "POST",
                                quietMillis: 50,
                                data: function (params) {
                                    var queryParameters = {
                                        term: params.term,
                                        webinar_id: $(this).data('webinar-id')
                                    }
                                    return queryParameters;
                                },
                                processResults: function (data) {
                                    return {
                                        results: $.map(data, function (item) {
                                            return {
                                                text: item.title,
                                                id: item.id
                                            }
                                        })
                                    };
                                }
                            }
                        });
                    }
                });
            }
        });
    });

    /*
   * edit FAQ
   * */
    function editFaq($this, locale = null) {
        const faq_id = $this.attr('data-faq-id');
        const webinar_id = $this.attr('data-webinar-id');

        const edit_data = {
            item_id: webinar_id,
            locale: locale
        };

        $.post('/admin/faqs/' + faq_id + '/edit', edit_data, function (result) {
            if (result && result.faq) {
                const faq = result.faq;

                let edit_faq_modal = '<div id="addFAQsModal">';
                edit_faq_modal += $('#webinarFaqModal').html();
                edit_faq_modal += '</div>';
                edit_faq_modal = edit_faq_modal.replaceAll('/admin/faqs/store', '/admin/faqs/' + faq_id + '/update');

                Swal.fire({
                    html: edit_faq_modal,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '48rem',
                    onOpen: () => {
                        var $modal = $('#addFAQsModal');

                        Object.keys(faq).forEach(key => {
                            $modal.find('[name="' + key + '"]').val(faq[key]);
                        });

                        var localeSelect = $modal.find('select[name="locale"]');

                        if (localeSelect) {
                            localeSelect.addClass('js-edit-faq-locale-ajax');
                            localeSelect.attr('data-faq-id', faq_id);
                            localeSelect.attr('data-webinar-id', webinar_id);
                        }
                    }
                });
            }
        });
    }

    $('body').on('click', '.edit-faq', function (e) {
        e.preventDefault();
        const $this = $(this);

        loadingSwl();

        editFaq($this);
    });

    $('body').on('change', '.js-edit-faq-locale-ajax', function (e) {
        e.preventDefault();
        const $this = $(this);
        const locale = $this.val();

        editFaq($this, locale);
    });

    $('body').on('click', '.js-get-faq-description', function (e) {
        e.preventDefault();
        const $this = $(this);
        const answer = $this.parent().find('input').val();

        var html = '<div class="my-2">' + answer + '</div>';

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '30rem',
        });
    });

    /*
   * edit FAQ
   * */
    $('body').on('click', '.edit-webinar-quiz', function (e) {
        e.preventDefault();
        const $this = $(this);
        const webinar_quiz_id = $this.attr('data-webinar-quiz-id');
        const webinar_id = $this.attr('data-webinar-id');

        loadingSwl();

        const edit_data = {
            item_id: webinar_id
        };

        $.post('/admin/webinar-quiz/' + webinar_quiz_id + '/edit', edit_data, function (result) {
            if (result && result.webinarQuiz) {
                const webinar_quiz = result.webinarQuiz;

                let edit_webinar_quiz_modal = '<div id="addQuizModal">';
                edit_webinar_quiz_modal += $('#quizzesModal').html();
                edit_webinar_quiz_modal += '</div>';
                edit_webinar_quiz_modal = edit_webinar_quiz_modal.replaceAll('/admin/webinar-quiz/store', '/admin/webinar-quiz/' + webinar_quiz_id + '/update');
                edit_webinar_quiz_modal = edit_webinar_quiz_modal.replaceAll('quiz-select2', 'quiz-select22');

                Swal.fire({
                    html: edit_webinar_quiz_modal,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '30rem',
                    onOpen: () => {

                        $('.quiz-select22').append('<option selected="selected" value="' + webinar_quiz.id + '">' + webinar_quiz.title + '</option>');
                        $(".quiz-select22").select2({
                            placeholder: $(this).data('placeholder'),
                            allowClear: true,
                            width: '100%',
                        });

                        $('#addQuizModal').find('[name="chapter_id"]').val(webinar_quiz.chapter_id);
                    }
                });
            }
        });
    });

    /*
    * ./
    * */

    $('body').on('change', 'select[name="type"]', function () {
        const value = this.value;
        const webinarItem = ['capacity', 'start_date'];

        let show = true;

        if (value !== 'webinar') {
            show = false;
        }

        for (let item of webinarItem) {
            if (show) {
                $('.js-' + item).removeClass('d-none');
            } else {
                $('.js-' + item).addClass('d-none');
            }
        }
    });

    $('body').on('change', '.js-sequence-content-switch', function () {
        const parent = $(this).closest('.js-content-form');

        const sequenceContentInputs = parent.find('.js-sequence-content-inputs');
        sequenceContentInputs.addClass('d-none');

        if (this.checked) {
            sequenceContentInputs.removeClass('d-none');
        }
    });


    $('body').on('click', '#bundleAddNewCourses', function (e) {
        e.preventDefault();
        let html = '<div id="addBundleWebinarModal">';
        html += $('#bundleWebinarsModal').html();
        html += '</div>';
        html = html.replaceAll('bundleWebinars-select', 'bundleWebinars-select2');
        html = html.replaceAll('str_', '');

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
            onOpen: () => {
                $('.bundleWebinars-select2').select2({
                    allowClear: false,
                });
            },
        });
    });

    $('body').on('click', '#saveBundleWebinar', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $('#addBundleWebinarModal .js-form');

        handleWebinarItemForm(form, $this);
    });

    $('body').on('click', '.edit-bundle-webinar', function (e) {
        e.preventDefault();
        const $this = $(this);
        const item_id = $this.attr('data-item-id');
        const bundle_id = $this.attr('data-bundle-id');

        loadingSwl();

        const edit_data = {
            item_id: bundle_id
        };

        $.post('/admin/bundle-webinars/' + item_id + '/edit', edit_data, function (result) {
            if (result && result.bundleWebinar) {
                const bundleWebinar = result.bundleWebinar;

                let html = '<div id="addBundleWebinarModal">';
                html += $('#bundleWebinarsModal').html();
                html += '</div>';
                html = html.replaceAll('bundleWebinars-select', 'bundleWebinars-select2');
                html = html.replaceAll('/admin/bundle-webinars/store', '/admin/bundle-webinars/' + item_id + '/update');
                html = html.replaceAll('str_', '');

                Swal.fire({
                    html: html,
                    showCancelButton: false,
                    showConfirmButton: false,
                    customClass: {
                        content: 'p-0 text-left',
                    },
                    width: '48rem',
                    onOpen: () => {
                        var select = $('.bundleWebinars-select2');

                        select.val(bundleWebinar.webinar_id);
                        select.select2({
                            allowClear: false,
                        });
                    }
                });
            }
        });
    });


    /* feather icons */
    // **
    // **
    feather.replace();
})(jQuery);
