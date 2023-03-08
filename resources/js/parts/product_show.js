(function () {
    "use strict";

    var offerCountDown = $('#offerCountDown');

    if (offerCountDown.length) {
        var endtimeDate = offerCountDown.attr('data-day');
        var endtimeHours = offerCountDown.attr('data-hour');
        var endtimeMinutes = offerCountDown.attr('data-minute');
        var endtimeSeconds = offerCountDown.attr('data-second');

        offerCountDown.countdown100({
            endtimeYear: 0,
            endtimeMonth: 0,
            endtimeDate: endtimeDate,
            endtimeHours: endtimeHours,
            endtimeMinutes: endtimeMinutes,
            endtimeSeconds: endtimeSeconds,
            timeZone: ""
        });
    }

    function handleImageLazy() {
        var lazyImages = document.querySelectorAll('.lazyImage img');

        for (var img of lazyImages) {
            if (!img.complete) {
                img.parentNode.classList.add('lazyImageWaiting');

                img.addEventListener('load', function (e) {
                    e.currentTarget.parentNode.classList.remove('lazyImageWaiting');
                }, false);
            }
        }
    }

    $('body').on('click', '.product-show-thumbnail-card .thumbnail-card', function (e) {
        e.preventDefault();
        const $this = $(this);

        const newPath = $this.find('img').attr('src');

        $('.product-show-image-card img.main-s-image').attr('src', newPath);

        const $productDemoVideoBtn = $('#productDemoVideoBtn');

        if ($productDemoVideoBtn.length) {
            if ($this.hasClass('is-first-thumbnail-card')) {
                $productDemoVideoBtn.addClass('d-flex').removeClass('d-none');
            } else {
                $productDemoVideoBtn.addClass('d-none').removeClass('d-flex');
            }
        }

        handleImageLazy();
    });

    function handleQuantityValue(type) {
        const input = $('.cart-quantity input[name="quantity"]');

        let value = input.val();

        const productAvailabilityCount = $('#productAvailabilityCount').val();

        if (type === 'minus' && value > 1) {
            value = Number(value) - 1;
        } else if (type === 'plus') {
            value = Number(value) + 1;
        }

        if (!isNaN(productAvailabilityCount) && value > Number(productAvailabilityCount)) {
            value = Number(productAvailabilityCount);
        }

        input.val(value);

        const $productPoints = $('.js-product-points');
        const productRequirePointText = $('.js-product-require-point-text');

        if ($productPoints.length) {
            const requirePoint = value * $productPoints.val();

            $('.js-buy-with-point-show-btn').find('span').text(requirePoint);

            if (productRequirePointText.length) {
                productRequirePointText.find('span').text(value * $productPoints.val());
            }
        }
    }

    $('body').on('click', '.cart-quantity .minus', function (e) {
        e.preventDefault();

        handleQuantityValue('minus');
    });

    $('body').on('click', '.cart-quantity .plus', function (e) {
        e.preventDefault();

        handleQuantityValue('plus');
    });

    $('.barrating-stars select').each(function (index, element) {
        var $element = $(element);
        $element.barrating({
            theme: 'css-stars',
            readonly: false,
            initialRating: $element.data('rate'),
        });
    });

    $('body').on('click', '.js-share-product', function (e) {
        e.preventDefault();

        Swal.fire({
            html: $('#productShareModal').html(),
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            onOpen: () => {
                $('[data-toggle="tooltip"]').tooltip();
            },
            width: '32rem',
        });
    });

    $('body').on('click', '.js-buy-with-point', function (e) {
        e.preventDefault();

        Swal.fire({
            html: $('#buyWithPointModal').html(),
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '28rem',
        });
    });

    $('body').on('click', '.js-buy-product-with-point-modal-btn', function (e) {
        const action = $(this).attr('data-action');
        const form = $('#productAddToCartForm');

        form.attr('action', action);
        form.trigger('submit');
    });

    $('body').on('click', '#productDemoVideoBtn', function (e) {
        e.preventDefault();
        let path = $(this).attr('data-video-path');
        const height = 'auto';

        const videoTagId = 'demoVideoPlayer';
        const {html, options} = makeVideoPlayerHtml(path, 'local', height, videoTagId);

        let modalHtml = '<div id="productDemoVideoModal" class="demo-video-modal">\n' +
            '<h3 class="section-title after-line font-20 text-dark-blue">' + productDemoLang + '</h3>\n' +
            '<div class="demo-video-card mt-25">\n';

        modalHtml += html;

        modalHtml += '</div></div>';

        Swal.fire({
            html: modalHtml,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '48rem',
            onOpen: () => {
                videojs(videoTagId, options);
            }
        });
    });

    $('body').on('click', '.js-online-show', function () {
        const path = $(this).attr('data-href');

        let html = `<div class="offline-modal">
            <h3 class="section-title after-line">${onlineViewerModalTitleLang}</h3>

            <div class="product-online-viewer-modal-body rounded-sm mt-15">
                <iframe src="/ViewerJS/index.html#${path}" class="w-100 h-100 rounded-sm" frameborder="0" allowfullscreen></iframe>
            </div>

            <div class="mt-15 d-flex align-items-center justify-content-end">
                <button type="button" class="btn btn-danger ml-10 close-swl btn-sm">${closeLang}</button>
            </div>
        </div>`;

        Swal.fire({
            html: html,
            showCancelButton: false,
            showConfirmButton: false,
            customClass: {
                content: 'p-0 text-left',
            },
            width: '75rem',
        });
    });

    $('body').on('click', '.js-product-share-link-copy', function (e) {
        e.preventDefault();

        $(this).attr('data-original-title', copiedLang)
            .tooltip('show');

        $(this).attr('data-original-title', copyLang);

        copyToClipboard();
    });

    function copyToClipboard() {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.css('position', 'absolute');

        $temp.val($('.js-product-share-link').html()).select();
        document.execCommand("copy");
        $temp.remove();
    }
})(jQuery);
