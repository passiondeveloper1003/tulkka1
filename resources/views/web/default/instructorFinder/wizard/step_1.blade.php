<div class="wizard-step-1">
    <h3 class="font-20 text-dark font-weight-bold">{{ trans('update.skill_topic') }}</h3>

    <span class="d-block mt-30 text-gray wizard-step-num">
        {{ trans('update.step') }} 1/4
    </span>

    <div class="form-group mt-30">
        <label class="input-label font-weight-500">{{ trans('update.which_category_are_you_interested') }}</label>

        <select name="language" class="form-control mt-20 @error('language') is-invalid @enderror">
            <option value="">{{ trans('webinars.select_category') }}</option>

            @if(!empty($categories))
                @foreach($categories as $category)
                    @if(!empty($category->subCategories) and count($category->subCategories))
                        <optgroup label="{{  $category->title }}">
                            @foreach($category->subCategories as $subCategory)
                                <option value="{{ $subCategory->id }}" @if(request()->get('language') == $subCategory->id) selected="selected" @endif>{{ $subCategory->title }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option value="{{ $category->id }}" @if(request()->get('language') == $category->id) selected="selected" @endif>{{ trans('site.'.$category->title) }}</option>
                    @endif
                @endforeach
            @endif
        </select>

        @error('language')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

</div>
