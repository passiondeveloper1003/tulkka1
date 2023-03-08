@extends('admin.layouts.app')


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.rewards_settings') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ trans('update.rewards_settings') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <form action="/admin/rewards/settings" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="page" value="general">
                                        <input type="hidden" name="name" value="rewards_settings">

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[status]" value="0">
                                                <input type="checkbox" name="value[status]" id="rewardsStatusSwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['status']) and $itemValue['status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="rewardsStatusSwitch">{{ trans('admin/main.active') }}</label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.admin_reward_setting_active_hint') }}</div>
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[exchangeable]" value="0">
                                                <input type="checkbox" name="value[exchangeable]" id="exchangeableSwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['exchangeable']) and $itemValue['exchangeable']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="exchangeableSwitch">{{ trans('update.exchangeable') }}                       </label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.admin_reward_setting_exchangeable_hint') }}</div>
                                        </div>

                                        <div id="exchangeableUnitInput" class="form-group {{ ((!empty($itemValue) and !empty($itemValue['exchangeable']) and $itemValue['exchangeable']) or (!empty($errors) and $errors->has('exchangeable_unit'))) ? '' : 'd-none' }}">
                                            <label>{{ trans('update.exchangeable_unit') }}</label>
                                            <input type="number" name="value[exchangeable_unit]" value="{{ (!empty($itemValue) and !empty($itemValue['exchangeable_unit'])) ? $itemValue['exchangeable_unit'] : old('exchangeable_unit') }}" class="form-control text-center  @error('exchangeable_unit') is-invalid @enderror"/>
                                            @error('exchangeable_unit')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                            <div class="text-muted text-small mt-1">{{ trans('update.exchangeable_unit_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.want_more_points_link') }}</label>
                                            <input type="text" name="value[want_more_points_link]" value="{{ (!empty($itemValue) and !empty($itemValue['want_more_points_link'])) ? $itemValue['want_more_points_link'] : old('want_more_points_link') }}" class="form-control  @error('value.want_more_points_link') is-invalid @enderror"/>
                                            @error('value.want_more_points_link')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                            <div class="text-muted text-small mt-1">{{ trans('update.want_more_points_link_hint') }}</div>
                                        </div>

                                        <button type="submit" class="btn btn-primary mt-1">{{ trans('admin/main.submit') }}</button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/admin/rewards_settings.min.js"></script>
@endpush
