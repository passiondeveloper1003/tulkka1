@extends(getTemplate() . '.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/owl-carousel2/owl.carousel.min.css">
    <style>
        .package_active {
            background-color: var(--primary);
        }

        .package_inactive {
            background-color: var(--gray);
        }
    </style>
@endpush

@section('content')
    <section class="cart-banner position-relative text-center">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center text-center">
                <div class="col-12 col-md-9 col-lg-7">
                    <h1 class="font-30 text-white font-weight-bold">{{trans('panel.subs_and_plans')}}</h1>
                </div>
            </div>
        </div>
    </section>




    @if (!empty($subscribes) and !$subscribes->isEmpty())
        <div class="position-relative subscribes-container pe-none user-select-none">
            <div id="parallax4" class="ltr d-none d-md-block">
                <div data-depth="0.2" class="gradient-box left-gradient-box"></div>
            </div>

            <section class="container home-sections home-sections-swiper">
                <div class="text-center">
                    <h2 class="section-title">{{trans('panel.packages')}}</h2>
                    <p class="section-hint">{{ trans('home.subscribe_now_hint') }}</p>
                </div>

                <div class="position-relative mt-30">
                    <div class=" px-12">
                        <div class="row py-20">

                            @foreach ($subscribes as $subscribe)
                                <div @if (isset($authUser) && !$authUser->isTeacher() && !$authUser->isPaidUser()) onclick='Livewire.emit("showModal","SomeData")' @endif
                                    class="col-md-3 col-12">
                                    <div
                                        class="subscribe-plan position-relative bg-white d-flex flex-column align-items-center rounded-sm shadow pt-50 pb-20 px-20 bg-secondary ">
                                        @if ($subscribe->is_popular)
                                            <span
                                                class="badge badge-primary badge-popular px-15 py-5 ">{{ trans('panel.popular') }}</span>
                                        @endif

                                        {{-- <div class="plan-icon">
                                            <img src="{{ $subscribe->icon }}" class="img-cover" alt="">
                                        </div> --}}

                                        <h3 class="mt-20 font-30 text-white">{{ $subscribe->title }}</h3>
                                        <p class="font-weight-500 text-white mt-10 text-warning">{{ $subscribe->description }}</p>
                                        <div class="mt-30 font-14 text-white">Starting From</div>
                                        <div class="d-flex align-items-start text-primary mt-2">
                                            <span
                                                class="font-36 line-height-1 text-white">{{ addCurrencyToPrice($subscribe->price) }}</span>
                                        </div>

                                        <ul class="mt-20 plan-feature text-white">
                                          <li class="mt-10 text-white">{{ $subscribe->days }}
                                            {{ trans('financial.days_of_subscription') }}</li>
                                                  {{--
                                                <li class="mt-10">
                                                    @if ($subscribe->infinite_use)
                                                        {{ trans('update.unlimited') }}
                                                    @else
                                                        {{ $subscribe->usable_count }}
                                                    @endif --}}
                                            {{-- <span class="ml-5">{{ trans('update.subscribes') }}</span> --}}
                                            </li>
                                        </ul>

                                        @if (auth()->check())
                                            <button onclick='Livewire.emit("showModal","true")'
                                                class="btn btn-primary btn-block mt-50">{{ trans('financial.purchase') }}</button>
                                        @else
                                            <a href="/login" class="btn btn-primary btn-block mt-50">Choose The Package</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="swiper-pagination subscribes-swiper-pagination"></div>
                    </div>

                </div>
            </section>

            {{-- <div id="parallax5" class="ltr d-none d-md-block">
                <div data-depth="0.4" class="gradient-box right-gradient-box"></div>
            </div>

            <div id="parallax6" class="ltr d-none d-md-block">
                <div data-depth="0.6" class="gradient-box bottom-gradient-box"></div>
            </div> --}}
        </div>
    @endif



@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>
    <script src="/assets/default/vendors/owl-carousel2/owl.carousel.min.js"></script>
    <script src="/assets/default/vendors/parallax/parallax.min.js"></script>
    <script src="/assets/default/js/parts/home.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#btn_package_55").click(function() {
                $("#btn_package_55").removeClass("package_inactive");
                $("#btn_package_55").addClass("package_active");

                $("#btn_package_25").removeClass("package_active");
                $("#btn_package_25").addClass("package_inactive");
            });

            $("#btn_package_25").click(function() {
                $("#btn_package_25").removeClass("package_inactive");
                $("#btn_package_25").addClass("package_active");

                $("#btn_package_55").removeClass("package_active");
                $("#btn_package_55").addClass("package_inactive");
            });
        });
    </script>
@endpush
