<div class="wizard-step-1">
    <h3 class="font-20 text-dark font-weight-bold">{{ trans('update.meeting_type') }}</h3>

    <span class="d-block mt-30 text-gray wizard-step-num">
        {{ trans('update.step') }} 2/4
    </span>

    <span class="d-block font-16 font-weight-500 mt-30">{{ trans('update.lesson_goal') }}</span>

    <div class="form-group mt-10">


        <div class="d-flex align-items-center wizard-custom-radio mt-5 flex-wrap">
            <div id="1" class="wizard-custom-radio-item">
                <input type="radio" name="goals"  value="learn_basics" id="all"  class="">
                <label class="font-12 cursor-pointer" for="all">{{ trans('update.learn_basics') }}</label>
            </div>
            <div id="2" class="wizard-custom-radio-item">
                <input type="radio" name="goals" value="improve_proficiency"  id="improve_proficiency" class="">
                <label class="font-12 cursor-pointer" for="improve_proficiency">{{ trans('update.improve_proficiency') }}</label>
            </div>
            <div id="3" class="wizard-custom-radio-item">
                <input type="radio" name="goals" value="talk_with_people" id="talk_with_people" class="">
                <label class="font-12 cursor-pointer" for="talk_with_people">{{ trans('update.talk_with_people') }}</label>
            </div>
            <div id="4" class="wizard-custom-radio-item">
                <input type="radio" name="goals" value="do_business" id="do_business"  class="">
                <label class="font-12 cursor-pointer" for="do_business">{{ trans('update.do_business') }}</label>
            </div>
            <div id="5" class="wizard-custom-radio-item">
                <input type="radio" name="goals" value="for_kids" id="for_kids" class="">
                <label class="font-12 cursor-pointer" for="for_kids">{{ trans('update.for_kids') }}</label>
            </div>
            <div id="6" class="wizard-custom-radio-item">
                <input type="radio" name="goals" value="prepare_for_exams" id="prepare_for_exams"  class="">
                <label class="font-12 cursor-pointer" for="prepare_for_exams">{{ trans('update.prepare_for_exams') }}</label>
            </div>
        </div>
    </div>

    {{-- <div id="regionCard" class="d-none">
        <div class="form-group mt-30">
            <label class="input-label font-weight-500">{{ trans('update.country') }}</label>

            <select name="country_id" class="form-control">
                <option value="">{{ trans('update.select_country') }}</option>

                @if (!empty($countries))
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->title }}</option>
                    @endforeach
                @endif
            </select>
        </div>

        <div class="form-group mt-30">
            <label class="input-label font-weight-500">{{ trans('update.province') }}</label>

            <select name="province_id" class="form-control" disabled>
                <option value="">{{ trans('update.select_province') }}</option>
            </select>
        </div>

        <div class="form-group mt-30">
            <label class="input-label font-weight-500">{{ trans('update.city') }}</label>

            <select name="city_id" class="form-control" disabled>
                <option value="">{{ trans('update.select_city') }}</option>
            </select>
        </div>

        <div class="form-group mt-30">
            <label class="input-label font-weight-500">{{ trans('update.district') }}</label>

            <select name="district_id" class="form-control" disabled>
                <option value="">{{ trans('update.select_district') }}</option>
            </select>
        </div>
    </div> --}}


</div>
