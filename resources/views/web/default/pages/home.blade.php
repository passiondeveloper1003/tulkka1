@extends(getTemplate() . '.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/owl-carousel2/owl.carousel.min.css">
@endpush

@section('content')
    @if (isset($authUser) && !$authUser->isPaidUser() && $authUser->trial_expired)
        <div onclick='Livewire.emit("showModal","true")' class="cover-content pb-40 pt-20">
            <div class="container position-relative">
                @include('web.default.user.special_offer')
            </div>
        </div>
    @endif
    <div class="bg-homecolor">
        <section class="hero-section container py-30 py-md-60 pb-xl-200 pt-md-90">
            <div class="d-flex row position-relative">
                <div class="col-12 col-md-6 p-20 position-static">
                    @if (localeToCountryCode(mb_strtoupper(app()->getLocale())) == 'US')
                        <h2 class="hero-title text-center text-md-left">The <span class="text-primary">Easiest</span> and
                            <span class="text-primary">Most</span> <span class="text-primary">Affordable</span> Way to Learn
                            English
                        </h2>
                    @else
                        <h2 class="hero-title text-center text-lg-left ">
                            {{ trans('update.hero_title') }}
                        </h2>
                    @endif

                    <div
                        class="col-12 mt-50 mt-md-0 p-20 hero-video d-flex justify-content-center mb-md-50 mb-md-0 align-items-center ">
                        <div class="position-relative">
                            <a @if (!isset($authUser)) href="{{ url('login') }}" @else onclick='Livewire.emit("showModal","true")' @endif
                                class="bg-primary text-white position-absolute top-0 left-0 translate-middle py-2 p-30 rounded d-flex align-items-center d-none d-lg-flex btn btn-sm btn-primary nav-start-a-live-btn mr-2 ml-2 ils-20">
                                <!-- <span class="font-30">🎉</span> -->
                                <img src="/assets/default/img/home-private.png" />
                                <div class="ml-2">{{ trans('update.private_lessons') }}
                                </div>
                            </a>
                            <div class="custom-star position-absolute right-0"><img src="/assets/default/img/star.svg"
                                    alt="" /></div>
                            {{-- <img class="hero-student rounded" src="/assets/default/img/young-student.png" alt="" /> --}}
                            <video autoplay muted loop class="hero-student rounded">
                                <source src="/assets/default/videos/hero.mp4" type="video/mp4">
                            </video>
                            <div class="custom-mark position-absolute left-0 d-none d-md-block"><img
                                    src="/assets/default/img/arrow-1.svg" /></div>
                            <div class="custom-dots position-absolute"><img src="/assets/default/img/dots.png" /></div>
                        </div>
                    </div>
                    <div class="hero-mobile-flex">
                        <div class="mt-md-4 hero-section__second-title">{{ trans('update.modules') }}</div>
                        <div class="container-fluid p-2 p-md-0 hero-section__element-list mt-20">
                            <div class="row">
                                <div class="col-7 p-0 hero-circle-item"><i
                                        class="fa-solid fa-circle-check text-primary mx-2"></i><span>{{ trans('update.learning_methods') }}</span>
                                </div>
                                <div class="col-5 p-0 hero-circle-item"><i
                                        class="fa-solid fa-circle-check text-primary mx-2"></i><span>{{ trans('update.accessibility') }}</span>
                                </div>
                            </div>
                            <div class="row mt-2 mt-md-0">
                                <div class="col-7 p-0 hero-circle-item"><i
                                        class="fa-solid fa-circle-check text-primary mx-2"></i><span>{{ trans('update.tailored') }}</span>
                                </div>
                                <div class="col-5 p-0 hero-circle-item"><i
                                        class="fa-solid fa-circle-check text-primary mx-2"></i><span>{{ trans('update.1on1') }}</div>
                            </div>
                        </div>
                    </div>
                    

                    <div class="mt-4 d-flex justify-content-center justify-content-lg-start hero-features">

                    </div>
                    <div class="mt-lg-4 d-flex justify-content-center justify-content-lg-start">
                        <a @if (!isset($authUser)) href="{{ url('login') }}" @else onclick='Livewire.emit("showModal","true")' @endif
                            class="btn btn-primary px-sm-40 px-lg-50 text-nowrap font-16 font-weight-bold">{{ trans('update.subscribe') }}</a>
                        <a href="{{ url('instructor-finder?category_id=640') }}"
                            class="btn border-primary mx-2 px-sm-40 px-lg-50 text-nowrap font-16 font-weight-bold">{{ trans('update.teachers') }}</a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="stats-container py-60 py-md-80 py-xl-0">
        <div class="container bg-white br-10">
            <div class="row d-flex align-items-center p-0 p-md-40">
                <div class="col-12 col-md-4 mt-0 mt-md-25 mt-lg-0">
                    <div class="d-flex flex-row align-items-center text-left py-20 py-md-30 px-5 w-100">
                        <div
                            class="p-15 p-md-30 bg-primary rounded-circle text-center d-flex align-items-center justify-content-center">
                            <img class="stats-img" src="/assets/default/img/stats1.svg" alt="" />
                        </div>
                        <div class="d-flex flex-column ml-20">
                            <h4 class="stat-title mt-0 mt-md-4">{{ trans('home.made_for') }}</h4>
                            <p class="stat-desc mt-10">{{ trans('home.made_for_desc') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 mt-0 mt-md-25 mt-lg-0">
                    <div class="d-flex flex-row align-items-center text-left py-20 py-md-30 px-5 w-100">
                        <div
                            class="p-15 p-md-30 bg-primary rounded-circle text-center d-flex align-items-center justify-content-center">
                            <img class="stats-img" src="/assets/default/img/stats2.svg" alt="" />
                        </div>
                        <div class="d-flex flex-column ml-20">
                            <h4 class="stat-title mt-0 mt-md-4">{{ trans('home.support_services') }}</h4>
                            <p class="stat-desc mt-10">{{ trans('home.support_services_desc') }}</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4 mt-0 mt-md-25 mt-lg-0">
                    <div class="d-flex flex-row align-items-center text-left py-20 py-md-30 px-5 w-100">
                        <div
                            class="p-15 p-md-30 bg-primary rounded-circle text-center d-flex align-items-center justify-content-center">
                            <img class="stats-img" src="/assets/default/img/stats3.svg" alt="" />
                        </div>
                        <div class="d-flex flex-column ml-20">
                            <h4 class="stat-title mt-0 mt-md-4">{{ trans('home.easy_fun') }}</h4>
                            <p class="stat-desc mt-10">{{ trans('home.easy_fun_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-panel-bg pt-25 pb-40 py-md-100">

        @include('web.default.pages.broad-section')
    </div>

    <div class="py-100 container learn-more-section">
        <div class="row d-flex align-items-center">
            <div class="col-12 col-lg-5"
                onclick='Livewire.emit("showVideoModal","Tulkka Demonstration Video", "{{ $boxVideoOrImage['link'] ?? '' }}")'>
                <div class="custom-dots-video position-absolute"><img src="/assets/default/img/dots.png" /></div>
                <span class="play-button bg-primary rounded-circle d-flex align-items-center justify-content-center"><i
                        class="fa-solid fa-play text-white"></i></span>
                <img class="question-img" src="/assets/default/img/comp-video-bg.png" alt="" />
                <div class="d-none d-lg-flex custom-mark-video position-absolute"><img
                        src="/assets/default/img/sign-right.png" /></div>
            </div>
            <div class="col-2 d-none d-lg-flex"></div>
            <div class="col-12 col-lg-5 mt-md-0">
                <!-- <div><span class="bg-primary rounded-circle mr-2 p-2"><i class="fa-solid fa-fire text-white"></i></span>
                    {{ trans('update.join_teachers') }}</div> -->
                <h3 class="question-section-title mt-4 d-none d-lg-block">{{ trans('update.learn_more') }}</h3>
                <p class="font-16 mt-40 mt-lg-2">{{ trans('update.learn_more_desc') }}</p>
                <a class="btn btn-primary mt-2" href="/pages/about">{{ trans('update.discover_now') }}</a>
            </div>
        </div>
    </div>



    @foreach ($homeSections as $homeSection)
        @if (
            $homeSection->name == \App\Models\HomeSection::$testimonials and
                !empty($testimonials) and
                !$testimonials->isEmpty())
            <div class="bg-panel-bg">
                <div class="container position-relative testimonials-container py-40 py-md-100 ">
                    <section class="row home-sections d-flex align-items-center justify-content-center">
                        <div class="d-flex flex-column col-12 col-md-6 col-lg-4">
                            <h1 class="testimonial-title position-relative">{{ trans('home.testimonials') }}
                                <div class="custom-sweet-mark position-absolute"><img
                                        src="/assets/default/img/sweet_mark.png" /></div>
                                <div class="custom-star position-absolute right-0 d-none d-md-block"><img
                                        src="/assets/default/img/star.svg" alt="" /></div>
                            </h1>
                            <div class="d-flex align-items-center mt-20">
                                <img class="rounded-circle testimonial-img"
                                    src="{{ url('/store/1/default_images/testimonials/profile_picture%20(52).jpg') }}"
                                    alt="" />
                                <img class="rounded-circle testimonial-img"
                                    src="{{ url('/store/1/default_images/testimonials/profile_picture%20(28).jpg') }}"
                                    alt="" />
                                <img class="rounded-circle testimonial-img" src="/assets/default/img/testimonial.png"
                                    alt="" />
                                <span style="width: 49px;height: 49px;" class="bg-primary rounded-circle">
                                    <i style="width: 49px;height: 49px;" data-feather="plus" class=""></i>
                                </span>
                                <span class="mx-4 text-primary">{{ trans('update.review_120') }}</span>
                            </div>
                        </div>
                        <div class="position-relative home-sections-swiper d-flex col-12 col-md-6 col-lg-8 mt-4 mt-lg-0">
                            <div class="swiper-container testimonials-swiper px-12">
                                <div class="swiper-wrapper">
                                    @foreach ($testimonials as $testimonial)
                                        <div class="swiper-slide">
                                            <div
                                                class="testimonials-card position-relative py-15 py-lg-30 px-10 px-lg-20 rounded-sm shadow bg-white text-center">
                                                <div class="d-flex flex-column align-items-center justify-content-center">
                                                    <div class="d-flex flex-row align-items-center justify-content-center">
                                                        <img style="width: 60px; height: 60px;"
                                                            src="{{ $testimonial->user_avatar }}"
                                                            alt="{{ $testimonial->user_name }}" class="rounded-circle">
                                                        <div
                                                            class="d-flex flex-column align-items-center justify-content-center font-12 ml-2">
                                                            <h4 class="font-weight-bold text-secondary">
                                                                {{ $testimonial->user_name }}</h4>
                                                            <span
                                                                class="d-block font-14 text-gray">{{ $testimonial->user_bio }}</span>
                                                            @include('web.default.includes.webinar.rate', [
                                                                'rate' => $testimonial->rate,
                                                                'dontShowRate' => true,
                                                            ])
                                                        </div>

                                                    </div>
                                                </div>
                                                <p class="mt-25 text-gray font-14">{!! nl2br($testimonial->comment) !!}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>


                        </div>
                    </section>


                </div>
            </div>
        @endif
    @endforeach
@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/parts/time-counter-down.min.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/default/vendors/owl-carousel2/owl.carousel.min.js"></script>
    <script src="/assets/default/vendors/parallax/parallax.min.js"></script>
    <script src="/assets/default/vendors/video/video.min.js"></script>
    <script src="/assets/default/vendors/video/youtube.min.js"></script>
    <script src="/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/default/js/parts/home.min.js"></script>
    <script src="/assets/default/js/parts/webinar_show.min.js"></script>
@endpush
