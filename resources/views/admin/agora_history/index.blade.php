@extends('admin.layouts.app')

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
            <section class="card">
                <div class="card-header">
                    @can('admin_agora_history_export')
                        <div class="text-right">
                            <a href="/admin/agora_history/excel" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center font-14">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.course') }}</th>
                                <th class="text-left">{{ trans('admin/main.session') }}</th>
                                <th class="text-center">{{ trans('update.session_duration') }}</th>
                                <th class="text-center">{{ trans('admin/main.start_date') }}</th>
                                <th class="text-center">{{ trans('admin/main.end_date') }}</th>
                                <th class="text-center">{{ trans('update.meeting_duration') }}</th>
                            </tr>

                            @foreach($agoraHistories as $agoraHistory)
                                @php
                                    $meetingDuration = ($agoraHistory->end_at - $agoraHistory->start_at) / 60;
                                @endphp

                                <tr>
                                    <td class="text-left">{{ $agoraHistory->session->webinar->title }}</td>
                                    <td class="text-left">{{ $agoraHistory->session->title }}</td>
                                    <td>{{ convertMinutesToHourAndMinute($agoraHistory->session->duration) }}</td>
                                    <td>{{ dateTimeFormat($agoraHistory->start_at, 'j M Y | H:i') }}</td>
                                    <td>{{ dateTimeFormat($agoraHistory->end_at, 'j M Y | H:i') }}</td>
                                    <td class="{{ ($meetingDuration > $agoraHistory->session->duration) ? 'text-danger' : 'text-success' }}">
                                        {{ convertMinutesToHourAndMinute($meetingDuration) }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $agoraHistories->links() }}
                </div>
            </section>
        </div>
    </section>
@endsection
