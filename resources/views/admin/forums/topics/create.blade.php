@extends('admin.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/summernote/summernote-bs4.min.css">
@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="flex-grow-1">
                <h2 class="font-20 font-weight-bold">{{ $pageTitle }}</h2>

                <span class="d-block font-14 font-weight-500 text-gray mt-1">{{ trans('public.by') }} <span class="font-weight-bold">{{ $topic->creator->full_name }}</span> {{ trans('public.in') }} {{ dateTimeFormat($topic->created_at, 'j M Y | H:i') }}</span>
            </div>

            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{ trans('admin/main.dashboard') }}</a></div>
                <div class="breadcrumb-item"><a href="/admin/forums">{{ trans('update.forums') }}</a></div>
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 ">

                    <div class="card">
                        <div class="card-body">

                            <form action="/admin/forums/{{ !empty($topic) ? ($topic->forum_id.'/topics/'. $topic->id .'/update') : '/topics/store' }}" method="post">
                                {{ csrf_field() }}

                                <div class="">
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('update.topic_title') }}</label>
                                                <input type="text" name="title" value="{{ !empty($topic) ? $topic->title : old('title') }}" class="form-control @error('title') is-invalid @enderror" placeholder="{{ trans('update.topic_title_placeholder') }}">
                                                @error('title')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label class="input-label">{{ trans('update.forums') }}</label>
                                                <select name="forum_id" class="form-control @error('forum_id') is-invalid @enderror">
                                                    <option selected disabled>{{ trans('admin/main.choose_category') }}</option>

                                                    @foreach($forums as $forum)
                                                        @if(!empty($forum->subForums) and count($forum->subForums))
                                                            @php
                                                                $showOptgroup = false;

                                                                foreach($forum->subForums as $subForum) {
                                                                    if(!$subForum->close) {
                                                                        $showOptgroup = true;
                                                                    }
                                                                }
                                                            @endphp

                                                            @if($showOptgroup)
                                                                <optgroup label="{{ $forum->title }}">
                                                                    @foreach($forum->subForums as $subForum)
                                                                        @if(!$subForum->close)
                                                                            <option value="{{ $subForum->id }}" {{ ((!empty($topic) and $topic->forum_id == $subForum->id) or (request()->get('forum_id') == $subForum->id)) ? 'selected' : '' }}>{{ $subForum->title }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </optgroup>
                                                            @endif
                                                        @elseif(!$forum->close)
                                                            <option value="{{ $forum->id }}" {{ (request()->get('forum_id') == $forum->id) ? 'selected' : '' }}>{{ $forum->title }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('forum_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="input-label">{{ trans('public.description') }}</label>
                                                <textarea id="summernote" name="description" class="form-control @error('description')  is-invalid @enderror">{!! !empty($topic) ? $topic->description : old('description') !!}</textarea>
                                                @error('description')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-6">
                                            <div id="topicImagesInputs" class="create-topic-attachments form-group mt-2">
                                                <label class="input-label mb-0">{{ trans('update.attachments') }}</label>

                                                <div class="main-row input-group product-images-input-group mt-2">
                                                    <div class="input-group-prepend">
                                                        <button type="button" class="input-group-text admin-file-manager" data-input="attachments_record" data-preview="holder">
                                                            <i class="fa fa-upload"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="attachments[]" id="attachments_record" value="" class="form-control"/>

                                                    <button type="button" class="btn btn-primary btn-sm add-btn">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>

                                                @if(!empty($topic) and !empty($topic->attachments) and count($topic->attachments))
                                                    @foreach($topic->attachments as $topicAttachment)
                                                        <div class="input-group product-images-input-group mt-2">
                                                            <div class="input-group-prepend">
                                                                <button type="button" class="input-group-text admin-file-manager" data-input="attachments_{{ $topicAttachment->id }}" data-preview="holder">
                                                                    <i class="fa fa-upload"></i>
                                                                </button>
                                                            </div>
                                                            <input type="text" name="attachments[]" id="attachments_{{ $topicAttachment->id }}" value="{{ $topicAttachment->path }}" class="form-control" placeholder="{{ trans('update.attachments_size') }}"/>

                                                            <button type="button" class="btn btn-sm btn-danger remove-btn">
                                                                <i class="fa fa-times"></i>
                                                            </button>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                @error('images')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary mt-3">
                                    <i class="fa fa-file"></i>
                                    <span class="ml-1">{{ trans('update.publish_topic') }}</span>
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/summernote/summernote-bs4.min.js"></script>
    <script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
    <script src="/assets/default/js/parts/create_topics.min.js"></script>
@endpush
