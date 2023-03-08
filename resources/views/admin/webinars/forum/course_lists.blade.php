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

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th>{{trans('admin/main.id')}}</th>
                                        <th class="text-left">{{trans('admin/main.course')}}</th>
                                        <th class="text-left">{{trans('admin/main.instructor')}}</th>
                                        <th>{{trans('admin/main.question_count')}}</th>
                                        <th width="120">{{trans('admin/main.actions')}}</th>
                                    </tr>

                                    @foreach($webinars as $webinar)
                                        <tr class="text-center">
                                            <td>{{ $webinar->id }}</td>
                                            <td width="18%" class="text-left">
                                                <a class="text-primary mt-0 mb-1 font-weight-bold" href="{{ $webinar->getUrl() }}">{{ $webinar->title }}</a>
                                            </td>

                                            <td class="text-left">{{ $webinar->teacher->full_name }}</td>

                                            <td class="">{{ $webinar->forums_count }}</td>


                                            <td width="200" class="btn-sm">
                                                @can('admin_course_question_forum_list')
                                                    <a href="/admin/webinars/{{ $webinar->id }}/forums" target="_blank" class="btn-transparent btn-sm text-primary mt-1 mr-1" data-toggle="tooltip" data-placement="top" title="{{ trans('public.questions') }}">
                                                        <i class="fa fa-question"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $webinars->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
