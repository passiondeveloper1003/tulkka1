@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ $pageTitle }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.classes')}}</div>

                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="row">


            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-question"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('admin/main.question_count')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalQuestions }}
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('update.resolved')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $resolvedCount }}
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-hourglass"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('update.not_resolved')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $notResolvedCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section-body">

            <section class="card">
                <div class="card-body">
                    <form method="get" class="mb-0">

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.search')}}</label>
                                    <input type="text" name="title" value="{{ request()->get('title') }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="date" value="{{ request()->get('date') }}" placeholder="Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.user')}}</label>

                                    <select name="user_id" class="form-control search-user-select2"
                                            data-placeholder="{{ trans('public.search_user') }}">

                                        @if(!empty($user))
                                            <option value="{{ $user->id }}" selected>{{ $user->full_name }}</option>
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" data-plugin-selectTwo class="form-control populate">
                                        <option value="">{{trans('admin/main.all_status')}}</option>
                                        <option value="resolved" @if(request()->get('status') == 'resolved') selected @endif>{{trans('update.resolved')}}</option>
                                        <option value="not_resolved" @if(request()->get('status') == 'not_resolved') selected @endif>{{trans('update.not_resolved')}}</option>
                                        <option value="pined" @if(request()->get('status') == 'pined') selected @endif>{{trans('update.pined')}}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group mt-1">
                                    <label class="input-label mb-4"> </label>
                                    <input type="submit" class="text-center btn btn-primary w-100" value="{{trans('admin/main.show_results')}}">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </section>

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th class="text-left">{{trans('update.question_title')}}</th>
                                        <th class="">{{trans('admin/main.created_at')}}</th>
                                        <th class="">{{trans('admin/main.updated_at')}}</th>
                                        <th class="">{{trans('admin/main.creator')}}</th>
                                        <th>{{trans('public.answers')}}</th>
                                        <th>{{trans('update.pined')}}</th>
                                        <th>{{trans('update.resolved')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($forums as $forum)
                                        <tr class="text-center">
                                            <td width="18%" class="text-left">
                                                <span class="font-weight-bold">{{ $forum->title }}</span>
                                            </td>

                                            <td class="">{{ dateTimeFormat($forum->created_at, 'j M Y | H:i') }}</td>

                                            <td class="">
                                                @if(!empty($forum->last_answer))
                                                    {{ dateTimeFormat($forum->last_answer->created_at, 'j M Y | H:i') }}
                                                @else
                                                    --
                                                @endif
                                            </td>

                                            <td class="">{{ $forum->user->full_name }}</td>

                                            <td class="">{{ $forum->answers_count }}</td>

                                            <td class="">
                                                @if($forum->pin)
                                                    {{ trans('admin/main.yes') }}
                                                @else
                                                    {{ trans('admin/main.no') }}
                                                @endif
                                            </td>

                                            <td class="">
                                                @if(!empty($forum->resolved))
                                                    {{ trans('admin/main.yes') }}
                                                @else
                                                    {{ trans('admin/main.no') }}
                                                @endif
                                            </td>


                                            <td width="200" class="btn-sm">
                                                @can('admin_course_question_forum_answers')
                                                    <a href="/admin/webinars/{{ $forum->webinar_id }}/forums/{{ $forum->id }}/answers" target="_blank" class="btn-transparent btn-sm text-primary mt-1 mr-1" data-toggle="tooltip" data-placement="top" title="{{ trans('public.answers') }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $forums->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
