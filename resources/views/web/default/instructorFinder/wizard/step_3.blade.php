<div class="wizard-step-1">
    <h3 class="font-20 text-dark font-weight-bold">{{ trans('update.your_skill_level') }}</h3>

    <span class="d-block mt-30 text-gray wizard-step-num">
        {{ trans('update.step') }} 3/4
    </span>

    <div class="form-group mt-20">
      <label for="level_of_training">{{ trans('update.student_level') }}</label>

      <select name="level_of_training" class="form-control">
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

</div>
