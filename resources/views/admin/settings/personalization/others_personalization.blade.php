@php
    if (!empty($itemValue) and !is_array($itemValue)) {
        $itemValue = json_decode($itemValue, true);
    }

@endphp

@push('styles_top')

@endpush

<div class="mt-3" id="others_personalization">

    <form action="/admin/settings/others_personalization" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="page" value="personalization">
        <input type="hidden" name="others_personalization" value="others_personalization">



        <div class="row">
            <div class="col-12 col-md-6">
                <h4 class="text-dark font-24">{{ trans('update.guarantee') }}</h4>

                <div class="form-group custom-switches-stacked mb-0 mt-2">
                    <label class="custom-switch pl-0">
                        <input type="hidden" name="value[show_guarantee_text]" value="0">
                        <input type="checkbox" name="value[show_guarantee_text]" id="showGuaranteeTextSwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['show_guarantee_text']) and $itemValue['show_guarantee_text']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                        <span class="custom-switch-indicator"></span>
                        <label class="custom-switch-description mb-0 cursor-pointer" for="showGuaranteeTextSwitch">{{ trans('admin/main.show') }}</label>
                    </label>
                </div>

                <div class="form-group">
                    <label>{{ trans('update.enter_guarantee_text') }}</label>
                    <input type="text" name="value[guarantee_text]" value="{{ (!empty($itemValue) and !empty($itemValue['guarantee_text'])) ? $itemValue['guarantee_text'] : old('guarantee_text') }}" class="form-control "/>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">{{ trans('admin/main.save_change') }}</button>
    </form>

</div>

@push('scripts_bottom')

@endpush
