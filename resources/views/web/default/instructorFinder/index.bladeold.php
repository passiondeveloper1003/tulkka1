@extends('web.default.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.markercluster/markerCluster.css">
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.markercluster/markerCluster.Default.css">
    <link rel="stylesheet" href="/assets/vendors/wrunner-html-range-slider-with-2-handles/css/wrunner-default-theme.css">
@endpush

@section('content')
    <div class="instructor-finder">

        @if((!empty($mapCenter) and is_array($mapCenter)))
            <section id="instructorFinderMap"
                     class="instructor-finder-map"
                     data-latitude="{{ $mapCenter[0] }}"
                     data-longitude="{{ $mapCenter[1] }}"
                     data-zoom="{{ $mapZoom }}"
            >

            </section>
        @endif

        <div class="container">

            <form id="filtersForm" action="/instructor-finder?{{ http_build_query(request()->all()) }}" method="get">

                @include('web.default.instructorFinder.components.top_filters')

                <div class="row flex-lg-row-reverse instructor-layout">
                    <div class="col-12 col-lg-8">

                        <div id="instructorsList">
                            @if($instructors->isNotEmpty())
                                @foreach($instructors as $instructor)
                                    @include('web.default.instructorFinder.components.instructor_card', ['instructor' => $instructor])
                                @endforeach
                            @else
                                @include('web.default.includes.no-result',[
                                           'file_name' => 'support.png',
                                           'title' => trans('update.instructor_finder_no_result'),
                                           'hint' => nl2br(trans('update.instructor_finder_no_result_hint')),
                                       ])
                            @endif
                        </div>

                        <div class="text-center">
                            <button type="button" id="loadMoreInstructors" data-url="/instructor-finder" class="btn btn-border-white mt-50 {{ ($instructors->lastPage() <= $instructors->currentPage()) ? ' d-none' : '' }}">{{ trans('site.load_more_instructors') }}</button>
                        </div>
                    </div>

                    <div class="col-12 col-lg-4">

                        @include('web.default.instructorFinder.components.filters')

                        @include('web.default.instructorFinder.components.time_filter')

                        @include('web.default.instructorFinder.components.location_filters')


                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@push('scripts_bottom')
    <script src="/assets/vendors/wrunner-html-range-slider-with-2-handles/js/wrunner-jquery.js"></script>
    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>
    <script src="/assets/vendors/leaflet/leaflet.markercluster/leaflet.markercluster-src.js"></script>
    <script src="/assets/default/vendors/swiper/swiper-bundle.min.js"></script>

    <script>
        var currency = '{{ $currency }}';
        var profileLang = '{{ trans('public.profile') }}';
        var hourLang = '{{ trans('update.hour') }}';
        var mapUsers = JSON.parse(@json($mapUsers->toJson()));
        var selectProvinceLang = '{{ trans('update.select_province') }}';
        var selectCityLang = '{{ trans('update.select_city') }}';
        var selectDistrictLang = '{{ trans('update.select_district') }}';
    </script>

    <script src="/assets/default/js/parts/get-regions.min.js"></script>
    <script src="/assets/default/js/parts/instructor-finder-wizard.min.js"></script>
    <script src="/assets/default/js/parts/instructors.min.js"></script>

    <script src="/assets/default/js/parts/instructor-finder.min.js"></script>
@endpush
