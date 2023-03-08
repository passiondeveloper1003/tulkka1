@extends('admin.layouts.app')


@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.new_discount') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.new_discount') }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-8 col-lg-6">
                            <form action="/admin/store/discounts/{{ !empty($discount) ? $discount->id.'/update' : 'store' }}"
                                  method="Post">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label>{{ trans('admin/main.title') }}</label>
                                    <input type="text" name="name"
                                           class="form-control  @error('name') is-invalid @enderror"
                                           value="{{ !empty($discount) ? $discount->name : old('name') }}"
                                           placeholder="{{ trans('admin/main.name_placeholder') }}"/>
                                    @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('update.product') }}</label>

                                    <select name="product_id" class="form-control search-product-select2 @error('product_id')  is-invalid @enderror"
                                            data-placeholder="Search and Select Product">

                                        @if(!empty($discount) and !empty($discount->product))
                                            <option value="{{ $discount->product->id }}" selected>{{ $discount->product->title }}</option>
                                        @endif
                                    </select>
                                    @error('product_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group ">
                                    <label>{{ trans('admin/main.discount_percentage') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-percentage"></i>
                                            </div>
                                        </div>
                                        <input type="number"
                                               name="percent" class="spinner-input form-control text-center  @error('percent') is-invalid @enderror"
                                               value="{{ !empty($discount) ? $discount->percent : old('percent') }}"
                                               maxlength="3" min="0" max="100">
                                        @error('percent')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.start_date') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="dateRangeLabel">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="start_date" class="form-control text-center datetimepicker"
                                               aria-describedby="dateRangeLabel"
                                               value="{{ !empty($discount) ? dateTimeFormat($discount->start_date,'Y-m-d H:i',false) : old('start_date') }}"/>
                                        @error('start_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror

                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.end_date') }}</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="dateRangeLabel">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="end_date" class="form-control text-center datetimepicker"
                                               aria-describedby="dateRangeLabel"
                                               value="{{ !empty($discount) ? dateTimeFormat($discount->end_date,'Y-m-d H:i',false) : old('end_date') }}"/>
                                        @error('end_date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="input-label">{{ trans('admin/main.usable_times') }}</label>
                                    <input type="number" name="count" class="form-control text-center @error('count') is-invalid @enderror"
                                           value="{{ !empty($discount) ? $discount->count : old('count') }}"/>
                                    @error('count')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>{{ trans('admin/main.status') }}</label>
                                    <select name="status" class="form-control custom-select @error('status')  is-invalid @enderror">
                                        <option value="active" {{ !empty($discount) and $discount->status == 'active' ? 'selected' : '' }}>{{ trans('panel.active') }}</option>
                                        <option value="inactive" {{ !empty($discount) and $discount->status == 'inactive' ? 'selected' : '' }}>{{ trans('panel.inactive') }}</option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class=" mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

