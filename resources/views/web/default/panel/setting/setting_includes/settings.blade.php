@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
@endpush

<section class="mt-30">
    <h2 class="section-title after-line">{{ trans('update.settings') }}</h2>

    <div class="row mt-20">
        <div class="col-12 col-lg-4">

            <div class="form-group mb-30 mt-30">
                <label class="input-label">{{ trans('update.gender') }}:</label>

                <div class="d-flex align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" name="gender" value="man"
                            {{ (!empty($user->gender) and $user->gender == 'man') ? 'checked="checked"' : '' }}
                            id="man" class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="man">{{ trans('update.man') }}</label>
                    </div>

                    <div class="custom-control custom-radio ml-15">
                        <input type="radio" name="gender" value="woman" id="woman"
                            {{ (!empty($user->gender) and $user->gender == 'woman') ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="woman">{{ trans('update.woman') }}</label>
                    </div>
                </div>
            </div>

            <div class="form-group mb-30">
                <label class="input-label">{{ trans('update.age') }}:</label>
                <input type="number" name="age" value="{{ !empty($user->age) ? $user->age : '' }}"
                    class="form-control">
            </div>

            <div class="form-group mb-30">
                <label class="input-label">{{ trans('update.meeting_type') }}:</label>

                <div class="d-flex align-items-center">
                    <div class="custom-control custom-radio">
                        <input type="radio" name="meeting_type" value="in_person" id="in_person"
                            {{ (!empty($user->meeting_type) and $user->meeting_type == 'in_person') ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="in_person">{{ trans('update.in_person') }}</label>
                    </div>

                    <div class="custom-control custom-radio ml-10">
                        <input type="radio" name="meeting_type" value="online" id="online"
                            {{ (!empty($user->meeting_type) and $user->meeting_type == 'online') ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="online">{{ trans('update.online') }}</label>
                    </div>

                    <div class="custom-control custom-radio ml-10">
                        <input type="radio" name="meeting_type" value="all" id="all"
                            {{ (!empty($user->meeting_type) and $user->meeting_type == 'all') ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="all">{{ trans('public.all') }}</label>
                    </div>
                </div>
            </div>

            <div class="form-group mb-30">
                <label class="input-label">{{ trans('update.level_of_training') }}:</label>

                <div class="d-flex align-items-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="level_of_training[]" value="beginner" id="beginner"
                            {{ (!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('beginner', $user->level_of_training)) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="beginner">{{ trans('update.beginner') }}</label>
                    </div>

                    <div class="custom-control custom-checkbox ml-10">
                        <input type="checkbox" name="level_of_training[]" value="middle" id="middle"
                            {{ (!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('middle', $user->level_of_training)) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="middle">{{ trans('update.middle') }}</label>
                    </div>

                    <div class="custom-control custom-checkbox ml-10">
                        <input type="checkbox" name="level_of_training[]" value="expert" id="expert"
                            {{ (!empty($user->level_of_training) and is_array($user->level_of_training) and in_array('expert', $user->level_of_training)) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="expert">{{ trans('update.expert') }}</label>
                    </div>
                </div>
            </div>
            <div class="form-group mb-30">
                <label class="input-label">{{ trans('update.goals') }}:</label>

                <div class="d-flex align-items-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="goals[]" value="learn_basics" id="learn_basics"
                            {{ (!empty($user->goals()) and in_array('learn_basics', $user->goals->pluck('goal_name')->toArray())) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="learn_basics">{{ trans('update.learn_basics') }}</label>
                    </div>

                    <div class="custom-control custom-checkbox ml-10">
                        <input type="checkbox" name="goals[]" value="improve_proficiency" id="improve_proficiency"
                            {{ (!empty($user->goals()) and in_array('improve_proficiency', $user->goals->pluck('goal_name')->toArray())) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="improve_proficiency">{{ trans('update.improve_proficiency') }}</label>
                    </div>

                    <div class="custom-control custom-checkbox ml-10">
                        <input type="checkbox" name="goals[]" value="talk_with_people" id="talk_with_people"
                            {{ (!empty($user->goals()) and in_array('talk_with_people', $user->goals->pluck('goal_name')->toArray())) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="talk_with_people">{{ trans('update.talk_with_people') }}</label>
                    </div>
                    <div class="custom-control custom-checkbox ml-10">
                        <input type="checkbox" name="goals[]" value="do_business" id="do_business"
                            {{ (!empty($user->goals()) and in_array('do_business', $user->goals->pluck('goal_name')->toArray())) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="do_business">{{ trans('update.do_business') }}</label>
                    </div>
                    <div class="custom-control custom-checkbox ml-10">
                        <input type="checkbox" name="goals[]" value="for_kids" id="for_kids"
                            {{ (!empty($user->goals()) and in_array('for_kids', $user->goals->pluck('goal_name')->toArray())) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="for_kids">{{ trans('update.for_kids') }}</label>
                    </div>
                    <div class="custom-control custom-checkbox ml-10">
                        <input type="checkbox" name="goals[]" value="prepare_for_exams" id="prepare_for_exams"
                            {{ (!empty($user->goals()) and in_array('prepare_for_exams', $user->goals->pluck('goal_name')->toArray())) ? 'checked="checked"' : '' }}
                            class="custom-control-input">
                        <label class="custom-control-label font-14 cursor-pointer"
                            for="prepare_for_exams">{{ trans('update.prepare_for_exams') }}</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h2 class="section-title after-line">{{ trans('update.region') }}</h2>

    <div class="row mt-30">
        <div class="col-12 col-lg-4">
            <div class="form-group ">
                <label class="input-label">{{ trans('update.country') }}:</label>

                <select name="country_id" class="form-control " {{ empty($countries) ? 'disabled' : '' }}>
                    <option value="">{{ trans('update.select_country') }}</option>

                    @if (!empty($countries))
                        @foreach ($countries as $country)
                            @php
                                $country->geo_center = \Geo::get_geo_array($country->geo_center);
                            @endphp

                            <option value="{{ $country->id }}"
                                data-center="{{ implode(',', $country->geo_center) }}"
                                {{ ($user->country_id == $country->id or old('country_id') == $country->id) ? 'selected' : '' }}>
                                {{ $country->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group mt-30">
                <label class="input-label">{{ trans('update.province') }}:</label>

                <select name="province_id" class="form-control " {{ empty($provinces) ? 'disabled' : '' }}>
                    <option value="">{{ trans('update.select_province') }}</option>

                    @if (!empty($provinces))
                        @foreach ($provinces as $province)
                            @php
                                $province->geo_center = \Geo::get_geo_array($province->geo_center);
                            @endphp

                            <option value="{{ $province->id }}"
                                data-center="{{ implode(',', $province->geo_center) }}"
                                {{ ($user->province_id == $province->id or old('province_id') == $province->id) ? 'selected' : '' }}>
                                {{ $province->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group mt-30">
                <label class="input-label">{{ trans('update.city') }}:</label>

                <select name="city_id" class="form-control " {{ empty($cities) ? 'disabled' : '' }}>
                    <option value="">{{ trans('update.select_city') }}</option>

                    @if (!empty($cities))
                        @foreach ($cities as $city)
                            @php
                                $city->geo_center = \Geo::get_geo_array($city->geo_center);
                            @endphp

                            <option value="{{ $city->id }}" data-center="{{ implode(',', $city->geo_center) }}"
                                {{ ($user->city_id == $city->id or old('city_id') == $city->id) ? 'selected' : '' }}>
                                {{ $city->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group mt-30">
                <label class="input-label">{{ trans('update.district') }}:</label>

                <select name="district_id" class="form-control " {{ empty($districts) ? 'disabled' : '' }}>
                    <option value="">{{ trans('update.select_district') }}</option>

                    @if (!empty($districts))
                        @foreach ($districts as $district)
                            @php
                                $district->geo_center = \Geo::get_geo_array($district->geo_center);
                            @endphp

                            <option value="{{ $district->id }}"
                                data-center="{{ implode(',', $district->geo_center) }}"
                                {{ ($user->district_id == $district->id or old('district_id') == $district->id) ? 'selected' : '' }}>
                                {{ $district->title }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group mb-30">
                <label class="input-label">{{ trans('update.address') }}:</label>
                <input type="text" name="address" value="{{ !empty($user->address) ? $user->address : '' }}"
                    class="form-control">
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="form-group">
                <input type="hidden" id="LocationLatitude" name="latitude"
                    value="{{ !empty($user->location) ? $user->location[0] : '' }}">
                <input type="hidden" id="LocationLongitude" name="longitude"
                    value="{{ !empty($user->location) ? $user->location[1] : '' }}">

                <div id="mapContainer" class="d-none">
                    <label class="input-label">{{ trans('update.select_location') }}</label>
                    <span class="d-block font-12 text-gray">{{ trans('update.select_location_hint') }}</span>

                    <div class="region-map mt-10" id="mapBox" data-zoom="12">
                        <img src="/assets/default/img/location.png" class="marker">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <h2 class="section-title after-line">{{ trans('panel.meeting_list') }}</h2>

    <div class="row mt-30">
        <div class="col-12">
            <a href="/panel/meetings/settings" class="text-primary">{{ trans('update.manage_meetings') }}</a>

            <div class="d-flex align-items-center mt-25">
                <div class="available-meetings">11:30 AM</div>
                <div class="available-meetings">11:30 AM</div>
            </div>
        </div>
    </div> --}}
</section>

@push('scripts_bottom')
    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>

    <script>
        var selectProvinceLang = '{{ trans('update.select_province') }}';
        var selectCityLang = '{{ trans('update.select_city') }}';
        var selectDistrictLang = '{{ trans('update.select_district') }}';
    </script>

    <script src="/assets/default/js/panel/user_settings_tab.min.js"></script>
@endpush
