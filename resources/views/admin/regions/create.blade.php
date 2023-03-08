@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">
            <section class="card">
                <div class="card-body">
                    <form action="{{ !empty($region) ? '/admin/regions/'.$region->id.'/update' : '/admin/regions/store' }}" method="post">
                        {{ csrf_field() }}

                        <input type="hidden" name="type" value="{{ !empty($region) ? $region->type : request()->get('type', \App\Models\Region::$country) }}">

                        <div class="row">
                            <div class="col-12 col-lg-6">

                                <div id="countrySelectBox" class="form-group {{ !empty($countries) ? '' : 'd-none' }}">
                                    <label class="input-label">{{ trans('update.countries') }}</label>
                                    <select name="country_id" class="form-control search-region-select2 @error('country_id') is-invalid @enderror" data-type="{{ \App\Models\Region::$country }}" data-placeholder="{{ trans('admin/main.search') }} {{ trans('update.countries') }}">

                                        @if(!empty($countries))
                                            <option value="">{{ trans('admin/main.select') }} {{ trans('update.country') }}</option>

                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" data-center="{{ implode(',', $country->geo_center) }}" {{ ((!empty($region) and $region->country_id == $country->id) or old('country_id') == $country->id) ? 'selected' : '' }}>{{ $country->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('country_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div id="provinceSelectBox" class="form-group {{ ((!empty($region) and ($region->type == \App\Models\Region::$city or $region->type == \App\Models\Region::$district)) or (!empty(request()->get('type')) and (request()->get('type') == \App\Models\Region::$city or request()->get('type') == \App\Models\Region::$district))) ? '' : 'd-none'}}">
                                    <label class="input-label">{{ trans('update.provinces') }}</label>

                                    <select name="province_id" {{ empty($provinces) ? 'disabled' : '' }} class="form-control @error('province_id') is-invalid @enderror">
                                        <option value="">{{ trans('admin/main.select') }} {{ trans('update.province') }}</option>

                                        @if(!empty($provinces))
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->id }}" data-center="{{ implode(',', $province->geo_center) }}" {{ ((!empty($region) and $region->province_id == $province->id) or old('province_id') == $province->id) ? 'selected' : '' }}>{{ $province->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    @error('province_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div id="citySelectBox" class="form-group {{ ((!empty($region) and $region->type == \App\Models\Region::$district) or (!empty(request()->get('type')) and request()->get('type') == \App\Models\Region::$district)) ? '' : 'd-none'}}">
                                    <label class="input-label">{{ trans('update.city') }}</label>

                                    <select name="city_id" {{ empty($cities) ? 'disabled' : '' }} class="form-control @error('city_id') is-invalid @enderror">
                                        <option value="">{{ trans('admin/main.select') }} {{ trans('update.city') }}</option>

                                        @if(!empty($cities))
                                            @foreach($cities as $city)
                                                <option value="{{ $city->id }}" data-center="{{ implode(',', $city->geo_center) }}" {{ ((!empty($region) and $region->city_id == $city->id) or old('city_id') == $city->id) ? 'selected' : '' }}>{{ $city->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    @error('city_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="" class="input-label">{{ trans('admin/main.title') }}</label>
                                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ !empty($region) ? $region->title : '' }}" placeholder="{{ trans('admin/main.title') }}">
                                    @error('title')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <input type="hidden" id="LocationLatitude" name="latitude" value="{{ $latitude }}">
                                    <input type="hidden" id="LocationLongitude" name="longitude" value="{{ $longitude }}">

                                    <label class="input-label">{{ trans('update.select_location') }}</label>
                                    <span class="d-block">{{ trans('update.select_location_hint') }}</span>

                                    <div class="region-map mt-2" id="mapBox"
                                         data-latitude="{{ $latitude }}"
                                         data-longitude="{{ $longitude }}"
                                         data-zoom="{{ (!empty($region) and $region->type !== \App\Models\Region::$country and $region->type !== \App\Models\Region::$province and !empty($region->geo_center)) ? 12 : 5 }}"
                                    >
                                        <img src="/assets/default/img/location.png" class="marker">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success mt-4">{{ trans('admin/main.save') }}</button>
                    </form>
                </div>
            </section>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>

    <script>
        var selectProvinceLang = '{{ trans('update.select_province') }}';
        var selectCityLang = '{{ trans('update.select_city') }}';
    </script>
    <script src="/assets/default/js/admin/regions_create.min.js"></script>
@endpush
