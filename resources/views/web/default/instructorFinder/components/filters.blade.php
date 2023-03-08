<div class="mt-30 mt-md-45 px-35 py-30 rounded-sm filters-container">
    <h3 class="category-filter-title font-20 font-weight-bold text-dark-blue">{{ trans('update.categories') }}</h3>

    {{-- <div class="form-group mt-20">
        <label for="category_id">{{ trans('public.category') }}</label>

        <select name="category_id" id="category_id" class="form-control">
            <option value="">{{ trans('webinars.select_category') }}</option>
            @if (!empty($categories))
                @foreach ($categories as $category)
                    @if (!empty($category->subCategories) and count($category->subCategories))
                        <optgroup label="{{ $category->title }}">
                            @foreach ($category->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}"
                                    @if (request()->get('category_id') == $subCategory->id) selected="selected" @endif>
                                    {{ $subCategory->title }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $category->id }}"
                            @if (request()->get('category_id') == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                    @endif
                @endforeach
            @endif
        </select>
    </div> --}}

    <div class="form-group mt-20">
        <label for="level_of_training">{{ trans('update.student_level') }}</label>

        <select name="level_of_training" class="form-control rounded font-16">
            <option value="">{{ trans('public.all') }}</option>
            <option value="dont_anything" {{ request()->get('level_of_training') == 'dont_anything' ? 'selected' : '' }}>
              {{ trans('update.dont_anything') }}</option>
            <option value="beginner" {{ request()->get('level_of_training') == 'beginner' ? 'selected' : '' }}>
                {{ trans('update.beginner') }}</option>
            <option value="middle" {{ request()->get('level_of_training') == 'middle' ? 'selected' : '' }}>
                {{ trans('update.middle') }}</option>
            <option value="expert" {{ request()->get('level_of_training') == 'expert' ? 'selected' : '' }}>
                {{ trans('update.expert') }}</option>
        </select>
    </div>
    <div class="form-group mt-20">
        <label for="goals">{{ trans('update.goals') }}</label>

        <select name="goals" class="form-control rounded font-16">
            <option value="">{{ trans('public.all') }}</option>
            <option value="learn_basics" {{ request()->get('goals') == 'learn_basics' ? 'selected' : '' }}>
                {{ trans('update.learn_basics') }}</option>
            <option value="improve_proficiency"
                {{ request()->get('level_of_training') == 'goals' ? 'selected' : '' }}>
                {{ trans('update.improve_proficiency') }}</option>
            <option value="talk_with_people"
                {{ request()->get('level_of_training') == 'goals' ? 'selected' : '' }}>
                {{ trans('update.talk_with_people') }}</option>
            <option value="do_business" {{ request()->get('goals') == 'do_business' ? 'selected' : '' }}>
                {{ trans('update.do_business') }}</option>
            <option value="for_kids" {{ request()->get('goals') == 'for_kids' ? 'selected' : '' }}>
                {{ trans('update.for_kids') }}</option>
            <option value="prepare_for_exams"
                {{ request()->get('goals') == 'prepare_for_exams' ? 'selected' : '' }}>
                {{ trans('update.prepare_for_exams') }}</option>
        </select>
    </div>
    {{-- <div class="form-group mt-20">
      <label for="teaching">{{ trans('update.teaching') }}</label>

      <select name="teaching" class="form-control rounded font-16">
          <option value="">{{ trans('public.all') }}</option>
          <option value="640" {{ request()->get('teaching') == '640' ? 'selected' : '' }}>
              {{ trans('site.English') }}</option>
          <option value="641"
              {{ request()->get('teaching') == '641' ? 'selected' : '' }}>
              {{ trans('site.French') }}</option>
          <option value="642"
              {{ request()->get('teaching') == '642' ? 'selected' : '' }}>
              {{ trans('site.Chinese Mandarin') }}</option>
          <option value="643" {{ request()->get('teaching') == '643' ? 'selected' : '' }}>
              {{ trans('site.Arabic') }}</option>
          <option value="644" {{ request()->get('teaching') == '644' ? 'selected' : '' }}>
              {{ trans('site.Spanish') }}</option>
      </select>
  </div> --}}
  <div class="form-group mt-20">
    <label for="also_speaking">{{ trans('update.also_speaking') }}</label>

    <select name="also_speaking" class="form-control rounded font-16">
        <option value="">{{ trans('public.all') }}</option>
        <option value="640" {{ request()->get('also_speaking') == '640' ? 'selected' : '' }}>
            {{ trans('site.English') }}</option>
        <option value="641"
            {{ request()->get('also_speaking') == '641' ? 'selected' : '' }}>
            {{ trans('site.French') }}</option>
        <option value="642"
            {{ request()->get('also_speaking') == '642' ? 'selected' : '' }}>
            {{ trans('site.Chinese Mandarin') }}</option>
        <option value="643" {{ request()->get('also_speaking') == '643' ? 'selected' : '' }}>
            {{ trans('site.Arabic') }}</option>
        <option value="644" {{ request()->get('also_speaking') == '644' ? 'selected' : '' }}>
            {{ trans('site.Spanish') }}</option>
    </select>
</div>

    {{-- <div class="form-group">
        <label for="gender">{{ trans('update.instructor_gender') }}</label>

        <select name="gender" id="gender" class="form-control">
            <option value="">{{ trans('public.all') }}</option>

            <option value="man" {{ request()->get('gender') == 'man' ? 'selected' : '' }}>
                {{ trans('update.man') }}</option>
            <option value="woman" {{ request()->get('gender') == 'woman' ? 'selected' : '' }}>
                {{ trans('update.woman') }}</option>
        </select>
    </div> --}}
{{--     <div class="form-group mt-20">
      <label for="language">{{ trans('update.language') }}</label>

      <select name="language" class="form-control">
          <option value="">{{ trans('public.all') }}</option>
          <option value="640" {{ request()->get('language') == '640' ? 'selected' : '' }}>
              {{ trans('site.English') }}</option>
          <option value="641"
              {{ request()->get('language') == '641' ? 'selected' : '' }}>
              {{ trans('site.French') }}</option>
          <option value="642"
              {{ request()->get('language') == '642' ? 'selected' : '' }}>
              {{ trans('site.Chinese Mandarin') }}</option>
          <option value="643" {{ request()->get('language') == '643' ? 'selected' : '' }}>
              {{ trans('site.Arabic') }}</option>
          <option value="644" {{ request()->get('language') == '644' ? 'selected' : '' }}>
              {{ trans('site.Spanish') }}</option>
      </select>
  </div> --}}

    {{-- <div class="form-group">
        <label for="instructor_type">{{ trans('update.instructor_type') }}</label>

        <select name="role" id="instructor_type" class="form-control">
            <option value="">{{ trans('public.all') }}</option>
            <option value="{{ \App\Models\Role::$teacher }}" {{ (request()->get('role') == \App\Models\Role::$teacher) ? 'selected' : '' }}>{{ trans('public.instructor') }}</option>
            <option value="{{ \App\Models\Role::$organization }}" {{ (request()->get('role') == \App\Models\Role::$organization) ? 'selected' : '' }}>{{ trans('home.organization') }}</option>
        </select>
    </div> --}}

    {{--     <div class="form-group">
        <label class="input-label">{{ trans('update.meeting_type') }}</label>

        <div class="d-flex align-items-center wizard-custom-radio mt-5">
            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="meeting_type" value="all" id="all" class="" {{ (request()->get('meeting_type') == 'all') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="all">{{ trans('public.all') }}</label>
            </div>

            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="meeting_type" value="in_person" id="in_person" class="" {{ (request()->get('meeting_type') == 'in_person') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="in_person">{{ trans('update.in_person') }}</label>
            </div>

            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="meeting_type" value="online" id="online" class="" {{ (request()->get('meeting_type') == 'online') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="online">{{ trans('update.online') }}</label>
            </div>
        </div>
    </div> --}}

    {{-- <div class="form-group">
        <label class="input-label">{{ trans('update.population') }}</label>

        <div class="d-flex align-items-center wizard-custom-radio mt-5">
            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="population" value="all" id="population_all" class="" {{ (request()->get('population') == 'all') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="population_all">{{ trans('public.all') }}</label>
            </div>

            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="population" value="single" id="population_single" class="" {{ (request()->get('population') == 'single') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="population_single">{{ trans('update.single') }}</label>
            </div>

            <div class="wizard-custom-radio-item flex-grow-1">
                <input type="radio" name="population" value="group" id="population_group" class="" {{ (request()->get('population') == 'group') ? 'checked' : '' }}>
                <label class="font-12 cursor-pointer px-15 py-10" for="population_group">{{ trans('update.group') }}</label>
            </div>
        </div>
    </div> --}}

    {{--   <div class="form-group pb-20">
        <label class="form-label">{{ trans('update.price_range') }}</label>
        <div
            class="range wrunner-value-bottom"
            id="priceRange"
            data-minLimit="0"
            data-maxLimit="1000"
        >
            <input type="hidden" name="min_price" value="{{ request()->get('min_price') ?? null }}">
            <input type="hidden" name="max_price" value="{{ request()->get('max_price') ?? null }}">
        </div>
    </div>

    <div class="form-group pb-20">
        <label class="form-label">{{ trans('update.instructor_age') }}</label>
        <div
            class="range wrunner-value-bottom"
            id="instructorAgeRange"
            data-minLimit="0"
            data-maxLimit="100"
        >
            <input type="hidden" name="min_age" value="{{ request()->get('min_age') ?? null }}">
            <input type="hidden" name="max_age" value="{{ request()->get('max_age') ?? null }}">
        </div>
    </div> --}}
</div>
