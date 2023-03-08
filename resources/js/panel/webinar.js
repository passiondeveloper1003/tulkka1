(function ($) {
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


    $('body').on('click', '#sendForReview', function (e) {
        $(this).addClass('loadingbar primary').prop('disabled', true);
        e.preventDefault();
        $('#forDraft').val(0);
        $('#webinarForm').trigger('submit');
    });

    $('body').on('click', '#saveAsDraft', function (e) {
        $(this).addClass('loadingbar primary').prop('disabled', true);
        e.preventDefault();
        $('#forDraft').val(1);
        $('#webinarForm').trigger('submit');
    });

    $('body').on('click', '#getNextStep', function (e) {
        $(this).addClass('loadingbar primary').prop('disabled', true);
        e.preventDefault();
        $('#forDraft').val(1);
        $('#getNext').val(1);
        $('#webinarForm').trigger('submit');
    });

    $('body').on('click', '.js-get-next-step', function (e) {
        e.preventDefault();

        if (!$(this).hasClass('active')) {
            $(this).addClass('loadingbar primary').prop('disabled', true);
            const step = $(this).attr('data-step');

            $('#getStep').val(step);
            $('#forDraft').val(1);
            $('#getNext').val(1);
            $('#webinarForm').trigger('submit');
        }
    });

    $('#partnerInstructorSwitch').on('change.bootstrapSwitch', function (e) {
        let isChecked = e.target.checked;

        if (isChecked) {
            $('#partnerInstructorInput').removeClass('d-none');
        } else {
            $('#partnerInstructorInput').addClass('d-none');
        }
    });

    function handleGetFiltersTitleFromTranslations(translations, defaultLocale) {
        let title = null;

        if (Object.keys(translations).length) {
            Object.keys(translations).forEach(key => {
                const translation = translations[key];

                if (translation.locale === defaultLocale) {
                    title = translation.title
                }
            })

            if (!title) {
                title = translations[0].title
            }
        }

        return title;
    }

    $('body').on('change', '#categories', function (e) {
        e.preventDefault();
        let category_id = this.value;
        $.get('/panel/filters/get-by-category-id/' + category_id, function (result) {

            if (result && typeof result.filters !== "undefined" && result.filters.length) {
                const defaultLocale = result.defaultLocale;
                let html = '';

                Object.keys(result.filters).forEach(key => {
                    let filter = result.filters[key];
                    let options = [];

                    if (filter.options.length) {
                        options = filter.options;
                    }

                    let filterTitle = filter.title;

                    if (!filterTitle && filter.translations) {
                        filterTitle = handleGetFiltersTitleFromTranslations(filter.translations, defaultLocale);
                    }

                    html += '<div class="col-12 col-md-3">\n' +
                        '<div class="webinar-category-filters">\n' +
                        '<strong class="category-filter-title d-block">' + filterTitle + '</strong>\n' +
                        '<div class="py-10"></div>\n' +
                        '\n';

                    if (options.length) {
                        Object.keys(options).forEach(index => {
                            let option = options[index];

                            let optionTitle = option.title;

                            if (!optionTitle && option.translations) {
                                optionTitle = handleGetFiltersTitleFromTranslations(option.translations, defaultLocale);
                            }

                            html += '<div class="form-group mt-20 d-flex align-items-center justify-content-between">\n' +
                                '<label class="cursor-pointer" for="filterOption' + option.id + '">' + optionTitle + '</label>\n' +
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

    $('body').on('click', '.cancel-accordion', function (e) {
        e.preventDefault();

        $(this).closest('.accordion-row').remove();
    });

    function randomString() {
        var text = "";
        var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

        for (var i = 0; i < 5; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }

    /*
    *
    * */

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

    /**
     * add ticket
     * */
    $('body').on('click', '#webinarAddTicket', function (e) {
        e.preventDefault();
        const key = randomString();

        let add_ticket = $('#newTicketForm').html();
        add_ticket = add_ticket.replaceAll('record', key);

        $('#ticketsAccordion').prepend(add_ticket);

        resetDatePickers();
        feather.replace();
    });

    $('body').on('click', '.js-save-ticket', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.ticket-form');

        handleWebinarItemForm(form, $this);
    });

    /*
    * add chapter
    * */

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

                    const path = '/panel/chapters/' + itemId + '/update';
                    modal.find('.chapter-form').attr('data-action', path);

                    $.get('/panel/chapters/' + itemId + '?locale=' + locale, function (result) {
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


    /**
     * add webinar sessions
     * */

    $('body').on('click', '.js-save-session', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.session-form');

        handleWebinarItemForm(form, $this);
    });

    /**
     * add webinar sessions
     * */

    $('body').on('click', '.js-save-assignment', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.assignment-form');

        handleWebinarItemForm(form, $this);
    });

    $('body').on('click', '.js-save-file', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.file-form');

        handleFileFormSubmit(form, $this);
    });


    $('body').on('click', '.js-save-text_lesson', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.text_lesson-form');

        handleWebinarItemForm(form, $this);
    });


    function setSelect2() {
        const selectItem = $('body #prerequisitesAccordion .prerequisites-select2');
        if (selectItem.length) {
            selectItem.select2({
                minimumInputLength: 3,
                allowClear: true,
                ajax: {
                    url: '/panel/webinars/search',
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (params) {
                        return {
                            term: params.term,
                            webinar_id: $(this).data('webinar-id')
                        };
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
            })
        }

        if ($('.accordion-content-wrapper .attachments-select2').length) {
            $('.accordion-content-wrapper .attachments-select2').select2({
                multiple: true,
                width: '100%',
            });
        }
    }

    $(document).ready(function () {

        var summernoteTarget = $('.accordion-content-wrapper .js-content-summernote');
        if (summernoteTarget.length) {
            summernoteTarget.summernote({
                tabsize: 2,
                height: 400,
                callbacks: {
                    onChange: function (contents, $editable) {
                        $('.js-hidden-content-summernote').val(contents);
                    }
                }
            });
        }


        setTimeout(() => {
            setSelect2();
        }, 1000);
    });
    /**
     * add webinar prerequisites
     * */
    $('body').on('click', '#webinarAddPrerequisites', function (e) {
        e.preventDefault();
        const key = randomString();

        let add_prerequisite = $('#newPrerequisiteForm').html();
        add_prerequisite = add_prerequisite.replaceAll('record', key);
        add_prerequisite = add_prerequisite.replaceAll('prerequisites-select2', 'prerequisites-select2-' + key);

        $('#prerequisitesAccordion').prepend(add_prerequisite);

        $('.prerequisites-select2-' + key).select2({
            placeholder: $(this).data('placeholder'),
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: '/panel/webinars/search',
                dataType: 'json',
                type: "POST",
                quietMillis: 50,
                data: function (params) {
                    return {
                        term: params.term,
                        webinar_id: $(this).data('webinar-id')
                    };
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
        })

        feather.replace();
    });

    $('body').on('click', '.js-save-prerequisite', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.prerequisite-form');
        handleWebinarItemForm(form, $this);
    });

    /**
     * add webinar FAQ
     * */
    $('body').on('click', '#webinarAddFAQ', function (e) {
        e.preventDefault();
        const key = randomString();

        let add_faq = $('#newFaqForm').html();
        add_faq = add_faq.replaceAll('record', key);

        $('#faqsAccordion').prepend(add_faq);

        feather.replace();
    });

    $('body').on('click', '.js-save-faq', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.faq-form');
        handleWebinarItemForm(form, $this);
    });

    $('body').on('click', '#add_new_learning_materials', function (e) {
        e.preventDefault();
        const key = randomString();

        let add_faq = $('#new_learning_materials_html').html();
        add_faq = add_faq.replaceAll('record', key);

        $('#learning_materials_accordion').prepend(add_faq);

        feather.replace();
    });

    $('body').on('click', '#add_new_company_logos', function (e) {
        e.preventDefault();
        const key = randomString();

        let add_faq = $('#new_company_logos_html').html();
        add_faq = add_faq.replaceAll('record', key);

        $('#company_logos_accordion').prepend(add_faq);

        feather.replace();
    });

    $('body').on('click', '#add_new_requirements', function (e) {
        e.preventDefault();
        const key = randomString();

        let add_faq = $('#new_requirements_html').html();
        add_faq = add_faq.replaceAll('record', key);

        $('#requirements_accordion').prepend(add_faq);

        feather.replace();
    });

    $('body').on('click', '.js-save-extra_description', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.extra_description-form');
        handleWebinarItemForm(form, $this);
    });

    /**
     * add webinar Quiz
     * */
    $('body').on('click', '#webinarAddQuiz', function (e) {
        e.preventDefault();
        const key = randomString();

        let add_quiz = $('#newQuizForm').html();
        add_quiz = add_quiz.replaceAll('record', key);

        $('#quizzesAccordion').prepend(add_quiz);

        feather.replace();
    });

    $('body').on('click', '.js-save-quiz', function (e) {
        e.preventDefault();
        const $this = $(this);
        let form = $this.closest('.quiz-form');
        handleWebinarItemForm(form, $this);
    });


    $(document).ready(function () {

        function updateToDatabase(table, idString) {
            $.post('/panel/webinars/order-items', {table: table, items: idString}, function (result) {
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
            });
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

        var target = $('.draggable-lists');
        var target2 = $('.draggable-lists2');
        var target3 = $('.draggable-lists3');

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

        setSortable(target);

        if (target2.length) {
            setSortable(target2);
        }

        if (target3.length) {
            setSortable(target3);
        }
    });

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

    $('body').on('click', '.js-s3-file-path-input button', function () {

        const parent = $(this).closest('.js-s3-file-path-input');
        parent.find('input').trigger('click')
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

    $('body').on('change', '.js-file-storage', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const formGroup = $(this).closest('.file-form');
        const fileType = formGroup.find('.js-ajax-file_type').val();

        handleShowFileInputsBySource(formGroup, value, fileType);
    });

    $('body').on('change', '.js-ajax-file_type', function (e) {
        e.preventDefault();

        const value = $(this).val();
        const formGroup = $(this).closest('.file-form');
        const source = formGroup.find('.js-file-storage').val();

        handleShowFileInputsBySource(formGroup, source, value);
    });

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

    $(document).ready(function () {
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
    });

    $('body').on('change', '.js-interactive-type', function () {
        const fileForm = $(this).closest('.file-form');

        const $fileName = fileForm.find('.js-interactive-file-name-input');
        $fileName.addClass('d-none');

        if ($(this).val() === 'custom') {
            $fileName.removeClass('d-none');
        }

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

    $('body').on('change', '.js-sequence-content-switch', function () {
        const parent = $(this).closest('.accordion-row');

        const sequenceContentInputs = parent.find('.js-sequence-content-inputs');
        sequenceContentInputs.addClass('d-none');

        if (this.checked) {
            sequenceContentInputs.removeClass('d-none');
        }
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

})(jQuery);
