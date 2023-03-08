@extends(getTemplate() . '.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
@endpush

@section('content')
    <section class="cart-banner position-relative ">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <div class="col-12 col-md-9 col-lg-7 text-center text-md-left">
                    <h1 class="font-36 text-white font-weight-bold text-left">{{ trans('navbar.about_us') }}</h1>
                    <!-- <h1 class="font-20 text-white mt-4">Home > About Us</h1> -->
                </div>
            </div>
        </div>
    </section>
    <section class="grow-section">
        <div class="container py-100">
            <div class="row">
                <div class="col-12 col-md-6">
                    <img class="grow-img" src="/assets/default/img/about-img.png" alt="">
                    <!-- <div class="custom-branding position-absolute"><img src="/assets/default/img/groww-img2.png" /></div> -->
                </div>
                <div class="col-12 col-md-6">
                    <h3 class="font-40 font-weight-normal text-primary mt-2">{{ trans('navbar.about_us') }}
                        <div class="custom-sweet-mark position-absolute"><img src="/assets/default/img/sweet_mark.png"></div>
                    </h3>
                    <p class="mt-3 font-16">{{trans('site.about_content_1')}}</p>
                    <p class="mt-50 font-16">{{trans('site.about_content_2')}}</p>
                    <p class="mt-50 font-16">{{trans('site.about_content_3')}}</p>
                    <!-- <a class="btn btn-primary rounded mt-40 px-100">Discover More</a> -->
                </div>
            </div>
        </div>
    </section>

    <div class="py-100 upgrade-section">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-12 col-md-6">
                    <i class="fa-solid fa-book text-primary"></i>
                    <span>Mission and Vision of Tulkka</span>
                    <h3 class="upgrade-title mt-4">Upgrade Your Skills
                        Build Your Life</h3>
                    <div class="mt-2"><i class="fa-solid fa-bullseye mr-2 text-primary font-16"></i>Mission of Tulkka
                    </div>
                    <div class="mt-2">Our Mission is to the provide the most convenient and affordable programs and
                        lessons aimed to help all types of learners with their language and educational needs.</div>
                    <div class="mt-4"><i class="fa-solid fa-bullseye mr-2 text-primary font-16"></i>Visions of Tulkka
                    </div>
                    <div class="mt-2">Our Vision is to become the number 1 online language and education provider in the
                        world. Accessibility and convenience are our tools to help educate anyone who is willing to learn
                        regardless of location and nationality.</div>
                </div>
                
                <div class="col-12 col-md-6 position-relative mt-100 mt-md-0">
                    <img class="upgrade-img" src="/assets/default/img/upgrade-img.png" alt="">
                    <div class="custom-upgrade position-absolute"><img class="custom-upgrade-img"
                            src="/assets/default/img/upgrade-img2.png" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="container mt-10 mt-md-40">
        <div class="row">
            <div class="col-12">
                <div class="mt-30">
                    @livewire('faq-section')
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/default/js/parts/home.min.js"></script>
@endpush
