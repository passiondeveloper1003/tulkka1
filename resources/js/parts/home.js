(function ($) {
  "use strict";

  new Swiper(".question-swiper-container", {
    slidesPerView: 1,
    spaceBetween: 10,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    observer: true,
    observeParents: true,
    parallax:true,
    pagination: {
      el: ".question-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      1200: {
        slidesPerView: 4,
      },
      992: {
        slidesPerView: 4,
      },
      768: {
        slidesPerView: 3,
      },
      576: {
        slidesPerView: 2,
      },
    },
    navigation: {
      nextEl: '.pag-right',
      prevEl: '.pag-left',
    },
  });

  new Swiper(".features-swiper-container", {
    slidesPerView: 1,
    spaceBetween: 0,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".features-swiper-pagination",
      clickable: true,
    },
  });

  new Swiper(".latest-webinars-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".latest-webinars-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 3,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  new Swiper(".latest-bundle-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".bundle-webinars-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 3,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  new Swiper(".best-sales-webinars-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".best-sales-webinars-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 3,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  new Swiper(".best-rates-webinars-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".best-rates-webinars-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 3,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  new Swiper(".has-discount-webinars-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".has-discount-webinars-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 3,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  new Swiper(".free-webinars-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".free-webinars-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 3,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  new Swiper(".new-products-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".new-products-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      1200: {
        slidesPerView: 4,
      },

      991: {
        slidesPerView: 3,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  new Swiper(".testimonials-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".testimonials-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 2,
      },

      660: {
        slidesPerView: 1,
      },
    },
  });

  new Swiper(".subscribes-swiper", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".subscribes-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 3,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  new Swiper(".organization-swiper-container", {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: false,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".organization-swiper-pagination",
      clickable: true,
    },
    breakpoints: {
      991: {
        slidesPerView: 4,
      },

      660: {
        slidesPerView: 2,
      },
    },
  });

  $(".instructors-swiper-container").owlCarousel({
    loop: true,
    center: true,
    items: 3,
    margin: 0,
    autoplay: true,
    dots: true,
    autoplayTimeout: 5000,
    smartSpeed: 450,
    responsive: {
      0: {
        items: 1,
      },
      768: {
        items: 2,
      },
      1170: {
        items: 4,
      },
    },
  });

  $("body").on("click", "#home-video", function (e) {
    e.preventDefault();
    let path = $(this).attr("href");
    let source = "";
    const height = $(window).width() > 991 ? 480 : 264;

    const videoTagId = "demoVideoPlayer";
    const { html, options } = makeVideoPlayerHtml(
      path,
      source,
      height,
      videoTagId
    );

    let modalHtml =
      '<div id="webinarDemoVideoModal" class="demo-video-modal">\n' +
      '<h3 class="section-title after-line font-20 text-dark-blue">' +
      "" +
      "</h3>\n" +
      '<div class="demo-video-card mt-25">\n';

    modalHtml += html;

    modalHtml += "</div></div>";

    Swal.fire({
      html: modalHtml,
      showCancelButton: false,
      showConfirmButton: false,
      customClass: {
        content: "p-0 text-left",
      },
      width: "48rem",
      onOpen: () => {
        videojs(videoTagId, options);
      },
    });
  });

  // $(document).ready(function () {
  //   for (var i = 1; i <= 6; i++) {`
  //     new Parallax(document.getElementById("parallax" + i), {
  //       relativeInput: true,
  //     });
  //   }
  // });
})(jQuery);
