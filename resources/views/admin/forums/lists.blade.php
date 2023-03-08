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
                <div class="breadcrumb-item">{{ $pageTitle }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-comment-dots"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_forums')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalForums }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-comment-alt"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_topics')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $totalTopics }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-comment"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.total_posts')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $postsCount }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-comments"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('update.active_members')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ $membersCount }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.icon') }}</th>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        @if(empty(request()->get('subForums')))
                                            <th>{{ trans('update.sub_forums') }}</th>
                                        @endif
                                        <th>{{ trans('update.topics') }}</th>
                                        <th>{{ trans('site.posts') }}</th>
                                        <th>{{ trans('admin/main.status') }}</th>
                                        <th>{{ trans('admin/main.closed') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($forums as $forum)

                                        <tr>
                                            <td>
                                                <img src="{{ $forum->icon }}" width="30" alt="">
                                            </td>
                                            <td class="text-left">
                                                @if(!empty($forum->subForums) and count($forum->subForums))
                                                    <a href="/admin/forums?subForums={{ $forum->id }}">{{ $forum->title }}</a>
                                                @else
                                                    <a href="/admin/forums/{{ $forum->id }}/topics">{{ $forum->title }}</a>
                                                @endif
                                            </td>
                                            @if(empty(request()->get('subForums')))
                                                <td>
                                                    @if(!empty($forum->subForums))
                                                        {{ count($forum->subForums) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $forum->topics_count }}</td>
                                            <td>{{ $forum->posts_count }}</td>
                                            <td>
                                                {{ trans('admin/main.'.$forum->status) }}
                                            </td>
                                            <td>
                                                @if($forum->close)
                                                    {{ trans('admin/main.yes') }}
                                                @else
                                                    {{ trans('admin/main.no') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($forum->subForums) and count($forum->subForums))
                                                    <a href="/admin/forums?subForums={{ $forum->id }}"
                                                       class="btn-transparent btn-sm text-primary mr-1"
                                                       data-toggle="tooltip" data-placement="top" title="{{ trans('update.forums') }}"
                                                    >
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                @else
                                                    @can('admin_forum_topics_lists')
                                                        <a href="/admin/forums/{{ $forum->id }}/topics"
                                                           class="btn-transparent btn-sm text-primary mr-1"
                                                           data-toggle="tooltip" data-placement="top" title="{{ trans('update.topics') }}"
                                                        >
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                @endif

                                                @can('admin_forum_edit')
                                                    <a href="/admin/forums/{{ $forum->id }}/edit"
                                                       class="btn-transparent btn-sm text-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('admin_forum_delete')
                                                    @include('admin.includes.delete_button',['url' => '/admin/forums/'.$forum->id.'/delete'])
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
