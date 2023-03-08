@extends('admin.layouts.app')

@push('styles_top')

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

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-pen"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('update.course_assignments')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $courseAssignmentsCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-eye"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('update.pending_review')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $pendingReviewCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('quiz.passed')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $passedCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-times"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{trans('quiz.failed')}}</h4>
                        </div>
                        <div class="card-body">
                            {{ $failedCount }}
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
                                    <label class="input-label">{{trans('admin/main.start_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="fsdate" class="text-center form-control" name="from" value="{{ request()->get('from') }}" placeholder="Start Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.end_date')}}</label>
                                    <div class="input-group">
                                        <input type="date" id="lsdate" class="text-center form-control" name="to" value="{{ request()->get('to') }}" placeholder="End Date">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.class')}}</label>
                                    <select name="webinar_ids[]" multiple="multiple" class="form-control search-webinar-select2"
                                            data-placeholder="Search classes">

                                        @if(!empty($webinars) and $webinars->count() > 0)
                                            @foreach($webinars as $webinar)
                                                <option value="{{ $webinar->id }}" selected>{{ $webinar->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="input-label">{{trans('admin/main.status')}}</label>
                                    <select name="status" class="form-control populate">
                                        <option value="">{{ trans('public.all') }}</option>
                                        <option value="active" {{ (request()->get('status') == 'active') ? 'selected' : '' }}>{{ trans('admin/main.active') }}</option>
                                        <option value="inactive" {{ (request()->get('status') == 'inactive') ? 'selected' : '' }}>{{ trans('admin/main.inactive') }}</option>
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

            <section class="card">
                <div class="card-body">
                    <table class="table table-striped font-14" id="datatable-details">

                        <tr>
                            <th>{{ trans('update.title_and_course') }}</th>
                            <th class="text-center">{{ trans('public.students') }}</th>
                            <th class="text-center">{{ trans('quiz.grade') }}</th>
                            <th class="text-center">{{ trans('update.pass_grade') }}</th>
                            <th class="text-center">{{ trans('public.status') }}</th>
                            <th class="text-right">{{ trans('admin/main.action') }}</th>
                        </tr>

                        @foreach($assignments as $assignment)
                            <tr>
                                <td class="text-left">
                                    <span class="d-block font-16 font-weight-500 text-dark-blue">{{ $assignment->title }}</span>
                                    <span class="d-block font-12 font-weight-500 text-gray">{{ $assignment->webinar->title }}</span>
                                </td>

                                <td class="align-middle">
                                    <span class="font-weight-500">{{ count($assignment->instructorAssignmentHistories) }}</span>
                                </td>

                                <td class="align-middle">
                                    <span>{{ $assignment->grade }}</span>
                                </td>

                                <td class="align-middle">
                                    <span>{{ $assignment->pass_grade }}</span>
                                </td>

                                <td class="align-middle">
                                    {{ trans('admin/main.'.$assignment->status) }}
                                </td>

                                <td class="align-middle text-right">
                                    @can('admin_reviews_status_toggle')
                                        <a href="/admin/assignments/{{ $assignment->id }}/students" class="btn-transparent text-primary mr-1" data-toggle="tooltip" data-placement="top" title="{{ trans('public.students') }}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    @endcan

                                    <a href="/admin/webinars/{{ $assignment->webinar_id }}/edit" target="_blank" class="btn-transparent text-primary" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.course') }}">
                                        <i class="fa fa-edit" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </section>
        </div>
    </section>

@endsection

@push('scripts_bottom')

@endpush
