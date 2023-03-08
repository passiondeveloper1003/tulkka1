/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */
(function ($) {
    "use strict";

    var datefilter = $('.datefilter');
    datefilter.daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    datefilter.on('apply.daterangepicker', function (ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD'));
    });

    datefilter.on('cancel.daterangepicker', function (ev, picker) {
        $(this).val('');
    });


    $('body').on('click', '.admin-file-manager', function (e) {
        e.preventDefault();
        $(this).filemanager('file', {prefix: '/laravel-filemanager'})
    });

    $('body').on('click', '.admin-file-view', function (e) {
        e.preventDefault();
        var input = $(this).attr('data-input');

        var img_src = $('#' + input).val();

        $('#fileViewModal').find('img').attr('src', img_src);
        $('#fileViewModal').modal('show');
    });


// ********************************************
// ********************************************
// date & time piker
    window.resetDatePickers = () => {
        if (jQuery().daterangepicker) {
            $('.date-range-picker').daterangepicker({
                locale: {format: 'YYYY-MM-DD'},
                drops: 'down',
                opens: 'right'
            });

            $('.datetimepicker').daterangepicker({
                locale: {format: 'YYYY-MM-DD H:mm'},
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
            });

            $('.datepicker').daterangepicker({
                locale: {format: 'YYYY-MM-DD'},
                singleDatePicker: true,
                timePicker: false,
            });
        }
    };
    resetDatePickers();

// Timepicker
    if (jQuery().timepicker) {
        $(".setTimepicker").each(function (key, item) {
            $(item).timepicker({
                icons: {
                    up: 'fa fa-chevron-up',
                    down: 'fa fa-chevron-down'
                },
                showMeridian: false,
            });
        })
    }

// ********************************************
// ********************************************
// select 2
    window.resetSelect2 = () => {
        if (jQuery().select2) {
            $(".select2").select2({
                placeholder: $(this).data('placeholder'),
                allowClear: true,
                width: '100%',
            });
        }
    };
    resetSelect2();
// ********************************************
// ********************************************
// inputtags
    if (jQuery().tagsinput) {
        var input_tags = $('.inputtags');
        input_tags.tagsinput({
            tagClass: 'badge badge-primary',
            maxTags: (input_tags.data('max-tag') ? input_tags.data('max-tag') : 10),
        });
    }

    window.handleSearchableSelect2 = function (className, path, itemColumn) {
        const $el = $('.' + className);

        if ($el.length) {
            $el.select2({
                placeholder: $el.attr('data-placeholder'),
                minimumInputLength: 3,
                allowClear: true,
                ajax: {
                    url: path,
                    dataType: 'json',
                    type: "POST",
                    quietMillis: 50,
                    data: function (params) {
                        return {
                            term: params.term,
                            option: $el.attr('data-search-option'),
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item[itemColumn],
                                    id: item.id
                                };
                            })
                        };
                    }
                }
            });
        }
    };

    $(document).ready(function () {

        handleSearchableSelect2('search-user-select2', '/admin/users/search', 'name');

        handleSearchableSelect2('search-user-select22', '/admin/users/search', 'name');

        handleSearchableSelect2('search-webinar-select2', '/admin/webinars/search', 'title');

        handleSearchableSelect2('search-bundle-select2', '/admin/bundles/search', 'title');

        handleSearchableSelect2('search-forum-topic-select2', '/admin/forums/topics/search', 'title');

        handleSearchableSelect2('search-product-select2', '/admin/store/products/search', 'title');

        handleSearchableSelect2('search-category-select2', '/admin/categories/search', 'title');

        handleSearchableSelect2('search-blog-select2', '/admin/blog/search', 'title');


        var datefilter = $('.datefilter');
        datefilter.daterangepicker({
            singleDatePicker: true,
            timePicker: false,
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        datefilter.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
        });

        datefilter.on('cancel.daterangepicker', function (ev, picker) {
            $(this).val('');
        });

        const sidebar_nicescroll = $(".main-sidebar").getNiceScroll();
        if (typeof sidebar_nicescroll !== "undefined" && sidebar_nicescroll.length) {
            const $active = $('.nav-item.active');

            if ($active && $active.length) {
                sidebar_nicescroll.doScrollPos(0, ($active.position().top - 100));
            }
        }
    });

    var lfm = function (options, cb) {
        var route_prefix = (options && options.prefix) ? options.prefix : '/laravel-filemanager';
        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
        window.SetUrl = cb;
    };

    // Define LFM summernote button
    var LFMButton = function (context) {
        var ui = $.summernote.ui;
        var button = ui.button({
            contents: '<i class="note-icon-picture"></i> ',
            tooltip: 'Insert image with filemanager',
            click: function () {

                lfm({type: 'file', prefix: '/laravel-filemanager'}, function (lfmItems, path) {
                    lfmItems.forEach(function (lfmItem) {
                        context.invoke('insertImage', lfmItem.url);
                    });
                });

            }
        });
        return button.render();
    };

    if (jQuery().summernote) {
        $(".summernote").summernote({
            dialogsInBody: true,
            tabsize: 2,
            height: $(".summernote").attr('data-height') ?? 250,
            fontNames: [],
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],

                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
                ['popovers', ['lfm']],
                ['paperSize', ['paperSize']], // The Button
            ],
            buttons: {
                lfm: LFMButton
            }
        });
    }


    $('body').on('change', '.js-edit-content-locale', function (e) {
        const val = $(this).val();

        if (val) {
            var url = window.location.origin + window.location.pathname;

            url += (url.indexOf('?') > -1) ? '&' : '?';

            url += 'locale=' + val;

            window.location.href = url;
        }
    });

    if ($(".colorpickerinput").length) {
        $(".colorpickerinput").colorpicker({
            format: 'hex',
            component: '.input-group-append',
        });
    }

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

    window.serializeObjectByTag = (tagId) => {
        var o = {};
        var a = tagId.find('input, textarea, select').serializeArray();
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

    //
    // delete sweet alert
    $('body').on('click', '.delete-action', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const href = $(this).attr('href');

        const title = $(this).attr('data-title') ?? deleteAlertHint;
        const confirm = $(this).attr('data-confirm') ?? deleteAlertConfirm;

        var html = '<div class="">\n' +
            '    <p class="">' + title + '</p>\n' +
            '    <div class="mt-30 d-flex align-items-center justify-content-center">\n' +
            '        <button type="button" id="swlDelete" data-href="' + href + '" class="btn btn-sm btn-primary">' + confirm + '</button>\n' +
            '        <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">' + deleteAlertCancel + '</button>\n' +
            '    </div>\n' +
            '</div>';

        Swal.fire({
            title: deleteAlertTitle,
            html: html,
            icon: 'warning',
            showConfirmButton: false,
            showCancelButton: false,
            allowOutsideClick: () => !Swal.isLoading(),
        })
    });

    $('body').on('click', '#swlDelete', function (e) {
        e.preventDefault();
        var $this = $(this);
        const href = $this.attr('data-href');

        $this.addClass('loadingbar primary').prop('disabled', true);

        $.get(href, function (result) {
            if (result && result.code === 200) {
                Swal.fire({
                    title: (typeof result.title !== "undefined") ? result.title : deleteAlertSuccess,
                    text: (typeof result.text !== "undefined") ? result.text : deleteAlertSuccessHint,
                    showConfirmButton: false,
                    icon: 'success',
                });

                if (typeof result.dont_reload === "undefined") {
                    setTimeout(() => {
                        if (typeof result.redirect_to !== "undefined" && result.redirect_to !== undefined && result.redirect_to !== null && result.redirect_to !== '') {
                            window.location.href = result.redirect_to;
                        } else {
                            window.location.reload();
                        }
                    }, 1000);
                }
            } else {
                Swal.fire({
                    title: deleteAlertFail,
                    text: deleteAlertFailHint,
                    icon: 'error',
                })
            }
        }).error(err => {
            Swal.fire({
                title: deleteAlertFail,
                text: deleteAlertFailHint,
                icon: 'error',
            })
        }).always(() => {
            $this.removeClass('loadingbar primary').prop('disabled', false);
        });
    })

})(jQuery);
