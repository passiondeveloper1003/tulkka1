@extends('admin.layouts.app')

@push('styles_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{ trans('admin/main.dashboard') }}</a>
                </div>
                <div class="breadcrumb-item active">
                    <a href="/admin/store/specifications">{{ trans('update.specifications') }}</a>
                </div>
                <div class="breadcrumb-item">{{ $pageTitle  }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form action="/admin/store/specifications/{{ !empty($specification) ? $specification->id.'/update' : 'store' }}"
                                  method="Post">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        @if(!empty(getGeneralSettings('content_translate')))
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('auth.language') }}</label>
                                                <select name="locale" class="form-control {{ !empty($specification) ? 'js-edit-content-locale' : '' }}">
                                                    @foreach($userLanguages as $lang => $language)
                                                        <option value="{{ $lang }}" @if(mb_strtolower(request()->get('locale', app()->getLocale())) == mb_strtolower($lang)) selected @endif>{{ $language }}</option>
                                                    @endforeach
                                                </select>
                                                @error('locale')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        @else
                                            <input type="hidden" name="locale" value="{{ getDefaultLocale() }}">
                                        @endif

                                        <div class="form-group">
                                            <label>{{ trans('/admin/main.title') }}</label>
                                            <input type="text" name="title"
                                                   class="form-control  @error('title') is-invalid @enderror"
                                                   value="{{ !empty($specification) ? $specification->title : old('title') }}"
                                                   placeholder="{{ trans('admin/main.choose_title') }}"/>
                                            @error('title')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-0">
                                    <label class="">{{ trans('admin/main.categories') }}</label>
                                    @error('category')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                <div class="row">
                                    @foreach($categories as $category)
                                        <div class="col-12 col-md-4 col-lg-3 mt-3">
                                            @if(!empty($category->subCategories) and count($category->subCategories))
                                                <div class="form-group mb-1">
                                                    <label class="">{{ $category->title }}</label>
                                                </div>

                                                @foreach($category->subCategories as $subCategory)
                                                    <div class="col-12 col-md-4 col-lg-3">
                                                        <div class="form-group mb-0">
                                                            <div class="custom-control custom-checkbox">
                                                                <input id="category{{ $subCategory->id }}" value="{{ $subCategory->id }}" type="checkbox" name="category[]"
                                                                       class="custom-control-input" {{ (!empty($selectedCategories) and in_array($subCategory->id,$selectedCategories)) ? 'checked' : '' }}>
                                                                <label class="custom-control-label"
                                                                       for="category{{ $subCategory->id }}">{{ $subCategory->title }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            @else
                                                <div class="form-group">
                                                    <div class="custom-control custom-checkbox">
                                                        <input id="category{{ $category->id }}" value="{{ $category->id }}" type="checkbox" name="category[]"
                                                               class="custom-control-input" {{ (!empty($selectedCategories) and in_array($category->id,$selectedCategories)) ? 'checked' : '' }}>
                                                        <label class="custom-control-label"
                                                               for="category{{ $category->id }}">{{ $category->title }}</label>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <div class="form-group mt-4">
                                    <label class="input-label">{{ trans('update.input_type') }}:</label>

                                    <div class="d-flex align-items-center" id="inputTypes">
                                        <div class="custom-control mr-2 custom-radio">
                                            <input type="radio" name="input_type" value="textarea" {{ (!empty($specification->input_type) and $specification->input_type == 'textarea') ? 'checked="checked"' : ''}} id="textarea" class="custom-control-input">
                                            <label class="custom-control-label cursor-pointer" for="textarea">{{ trans('update.textarea') }}</label>
                                        </div>

                                        <div class="custom-control mr-2 custom-radio ml-15">
                                            <input type="radio" name="input_type" value="multi_value" id="multi_value" {{ (!empty($specification->input_type) and $specification->input_type == 'multi_value') ? 'checked="checked"' : ''}} class="custom-control-input">
                                            <label class="custom-control-label cursor-pointer" for="multi_value">{{ trans('update.multi_value') }}</label>
                                        </div>
                                    </div>

                                    @error('input_type')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-6">
                                        <div id="multiValues" class="ml-0 {{ (!empty($multiValues) and !$multiValues->isEmpty()) ? '' : ' d-none' }}">
                                            <div class="d-flex align-items-center justify-content-between mb-4">
                                                <strong class="d-block">{{ trans('update.multi_value') }}</strong>

                                                <button type="button" class="btn btn-success add-btn"><i class="fa fa-plus"></i> Add</button>
                                            </div>

                                            <div class="multi-values-card">

                                                @if((!empty($multiValues) and !$multiValues->isEmpty()))
                                                    @foreach($multiValues as $key => $multiValue)
                                                        <div class="form-group">

                                                            <div class="input-group">
                                                                <input type="text" name="multi_values[{{ $multiValue->id }}][title]"
                                                                       class="form-control w-auto flex-grow-1"
                                                                       value="{{ !empty($multiValue->translate($selectedLocale)) ? $multiValue->translate($selectedLocale)->title : '' }}"
                                                                       placeholder="{{ trans('admin/main.choose_title') }}"/>

                                                                <div class="input-group-append">
                                                                    <button type="button" class="btn remove-btn btn-danger"><i class="fa fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button class="btn btn-primary">{{ trans('admin/main.submit') }}</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="form-group main-row d-none">
        <div class="input-group">
            <input type="text" name="multi_values[record][title]"
                   class="form-control w-auto flex-grow-1"
                   placeholder="{{ trans('admin/main.choose_title') }}"/>

            <div class="input-group-append">
                <button type="button" class="btn remove-btn btn-danger"><i class="fa fa-times"></i></button>
            </div>
        </div>
    </div>

@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/admin/store/specification.min.js"></script>
@endpush
