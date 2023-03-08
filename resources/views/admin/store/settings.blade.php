@extends('admin.layouts.app')


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-4">
                                    <form action="/admin/store/settings" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="page" value="general">
                                        <input type="hidden" name="name" value="{{ \App\Models\Setting::$storeSettingsName }}">

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[status]" value="0">
                                                <input type="checkbox" name="value[status]" id="storeStatusSwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['status']) and $itemValue['status']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="storeStatusSwitch">{{ trans('admin/main.active') }}</label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.admin_store_setting_active_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.virtual_product_commission') }}</label>
                                            <input type="number" name="value[virtual_product_commission]" value="{{ (!empty($itemValue) and !empty($itemValue['virtual_product_commission'])) ? $itemValue['virtual_product_commission'] : old('virtual_product_commission') }}" class="form-control text-center  @error('virtual_product_commission') is-invalid @enderror"/>
                                            @error('virtual_product_commission')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                            <div class="text-muted text-small mt-1">{{ trans('update.virtual_product_commission_setting_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.physical_product_commission') }}</label>
                                            <input type="number" name="value[physical_product_commission]" value="{{ (!empty($itemValue) and !empty($itemValue['physical_product_commission'])) ? $itemValue['physical_product_commission'] : old('physical_product_commission') }}" class="form-control text-center  @error('physical_product_commission') is-invalid @enderror"/>
                                            @error('physical_product_commission')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                            <div class="text-muted text-small mt-1">{{ trans('update.physical_product_commission_setting_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('admin/main.tax') }}</label>
                                            <input type="number" name="value[store_tax]" value="{{ (!empty($itemValue) and !empty($itemValue['store_tax'])) ? $itemValue['store_tax'] : old('store_tax') }}" class="form-control text-center  @error('store_tax') is-invalid @enderror"/>
                                            @error('store_tax')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                            <div class="text-muted text-small mt-1">{{ trans('update.admin_store_setting_tax_hint') }}</div>
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[possibility_create_virtual_product]" value="0">
                                                <input type="checkbox" name="value[possibility_create_virtual_product]" id="possibilityCreateVirtualProductSwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['possibility_create_virtual_product']) and $itemValue['possibility_create_virtual_product']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="possibilityCreateVirtualProductSwitch">{{ trans('update.possibility_create_virtual_product') }}</label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.possibility_create_virtual_product_hint') }}</div>
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[possibility_create_physical_product]" value="0">
                                                <input type="checkbox" name="value[possibility_create_physical_product]" id="possibilityCreatePhysicalProductSwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['possibility_create_physical_product']) and $itemValue['possibility_create_physical_product']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="possibilityCreatePhysicalProductSwitch">{{ trans('update.possibility_create_physical_product') }}</label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.possibility_create_physical_product_hint') }}</div>
                                        </div>

                                        <div class="form-group">
                                            <label>{{ trans('update.shipping_tracking_url') }}</label>
                                            <input type="text" name="value[shipping_tracking_url]" value="{{ (!empty($itemValue) and !empty($itemValue['shipping_tracking_url'])) ? $itemValue['shipping_tracking_url'] : old('shipping_tracking_url') }}" class="form-control  @error('value.shipping_tracking_url') is-invalid @enderror"/>
                                            @error('value.shipping_tracking_url')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror

                                            <div class="text-muted text-small mt-1">{{ trans('update.shipping_tracking_url_hint') }}</div>
                                        </div>

                                        <div class="form-group custom-switches-stacked">
                                            <label class="custom-switch pl-0 d-flex align-items-center">
                                                <input type="hidden" name="value[activate_comments]" value="0">
                                                <input type="checkbox" name="value[activate_comments]" id="activateCommentsSwitch" value="1" {{ (!empty($itemValue) and !empty($itemValue['activate_comments']) and $itemValue['activate_comments']) ? 'checked="checked"' : '' }} class="custom-switch-input"/>
                                                <span class="custom-switch-indicator"></span>
                                                <label class="custom-switch-description mb-0 cursor-pointer" for="activateCommentsSwitch">{{ trans('update.admin_store_setting_activate_comments') }}</label>
                                            </label>
                                            <div class="text-muted text-small">{{ trans('update.admin_store_setting_activate_comments_hint') }}</div>
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

@endpush
