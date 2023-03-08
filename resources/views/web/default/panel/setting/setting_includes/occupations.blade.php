<section class="mt-30">
    <h2 class="section-title after-line">{{ trans('site.languages') }}</h2>

    <div class="mt-20 d-flex align-items-center flex-wrap">
        @foreach ($categories as $category)
            @if (!empty($category->subCategories) and count($category->subCategories))
                @foreach ($category->subCategories as $subCategory)
                    <div class="checkbox-button mr-15 mt-10">
                        <input type="checkbox" name="languages[]" id="checkbox{{ $subCategory->id . '.language' }}"
                            value="{{ $subCategory->id . '.language' }}"
                            @if ($category->selected && ($category->type == 'language' || $category->type2 )  ) checked="checked" @endif>
                        <label class="font-14"
                            for="checkbox{{ $subCategory->id . '.language' }}">{{ $subCategory->title }}</label>
                    </div>
                @endforeach
            @else
                <div class="checkbox-button mr-15 mt-10">
                    <input type="checkbox" name="languages[]" id="checkbox{{ $category->id . '.language' }}"
                        value="{{ $category->id . '.language' }}"
                        @if ($category->selected && ($category->type == 'language' || $category->type2 )) checked="checked" @endif>
                    <label class="font-14" for="checkbox{{ $category->id . '.language' }}">{{ $category->title }}</label>
                </div>
            @endif
        @endforeach
    </div>

    <div class="mt-15">
        <p class="font-12 text-gray">- {{ trans('panel.interests_hint_1') }}</p>
        <p class="font-12 text-gray">- {{ trans('panel.interests_hint_2') }}</p>
    </div>

</section>
<section class="mt-30">
    <h2 class="section-title after-line">{{ trans('site.also_speaking') }}</h2>

    <div class="mt-20 d-flex align-items-center flex-wrap">
        @foreach ($categories as $category)
            @if (!empty($category->subCategories) and count($category->subCategories))
                @foreach ($category->subCategories as $subCategory)
                    <div class="checkbox-button mr-15 mt-10">
                        <input type="checkbox" name="also_speaking[]"
                            id="checkbox{{ $subCategory->id . '.also_speaking' }}"
                            value="{{ $subCategory->id . '.also_speaking' }}"
                            @if ($category->selected && $category->type == 'also_speaking') checked="checked" @endif>
                        <label class="font-14"
                            for="checkbox{{ $subCategory->id . '.also_speaking' }}">{{ $subCategory->title }}</label>
                    </div>
                @endforeach
            @else
                <div class="checkbox-button mr-15 mt-10">
                    <input type="checkbox" name="also_speaking[]" id="checkbox{{ $category->id . '.also_speaking' }}"
                        value="{{ $category->id . '.also_speaking' }}"
                        @if ($category->selected  && ($category->type == 'also_speaking' || $category->type2 ) ) checked="checked" @endif>
                    <label class="font-14"
                        for="checkbox{{ $category->id . '.also_speaking' }}">{{ $category->title }}</label>
                </div>
            @endif
        @endforeach
    </div>

    <div class="mt-15">
        <p class="font-12 text-gray">- {{ trans('panel.interests_hint_1') }}</p>
        <p class="font-12 text-gray">- {{ trans('panel.interests_hint_2') }}</p>
    </div>

</section>
