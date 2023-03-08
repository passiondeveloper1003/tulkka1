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

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center font-14">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.user') }}</th>
                                <th class="text-center">{{ trans('update.total_points') }}</th>
                                <th class="text-center">{{ trans('update.spent_points') }}</th>
                                <th class="text-center">{{ trans('update.available_points') }}</th>
                            </tr>

                            @foreach($rewards as $reward)

                                <tr>
                                    <td class="text-left">
                                        @if(!empty($reward->user))
                                            <div class="d-flex align-items-center">
                                                <figure class="avatar mr-2">
                                                    <img src="{{ $reward->user->getAvatar() }}" alt="{{ $reward->user->full_name }}">
                                                </figure>
                                                <div class="media-body ml-1">
                                                    <div class="mt-0 mb-1 font-weight-bold">{{ $reward->user->full_name }}</div>

                                                    @if($reward->user->mobile)
                                                        <div class="text-primary text-small font-600-bold">{{ $reward->user->mobile }}</div>
                                                    @elseif($reward->user->email)
                                                        <div class="text-primary text-small font-600-bold">{{ $reward->user->email }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </td>

                                    <td>{{ $reward->total_points }}</td>

                                    <td>{{ $reward->spent_points }}</td>

                                    <td>{{ $reward->available_points }}</td>

                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $rewards->links() }}
                </div>
            </section>
        </div>
    </section>
@endsection
