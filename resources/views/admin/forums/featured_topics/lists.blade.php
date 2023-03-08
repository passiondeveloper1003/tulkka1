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

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.icon') }}</th>
                                        <th>{{ trans('public.topic') }}</th>
                                        <th class="text-center">{{ trans('public.date') }}</th>
                                        <th>{{ trans('admin/main.actions') }}</th>
                                    </tr>

                                    @foreach($featuredTopics as $feature)

                                        <tr>
                                            <td>
                                                <img src="{{ $feature->icon }}" alt="" width="48" height="48" class="">
                                            </td>

                                            <td class="text-center">{{ $feature->topic->title }}</td>

                                            <td class="text-center">{{ dateTimeFormat($feature->created_at, 'j M Y | H:i') }}</td>

                                            <td width="150">

                                                @can('admin_featured_topics_edit')
                                                    <a href="/admin/featured-topics/{{ $feature->id }}/edit" class="btn-sm" data-toggle="tooltip" data-placement="top" title="{{ trans('admin/main.edit') }}">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan

                                                @can('admin_featured_topics_delete')
                                                    @include('admin.includes.delete_button',['url' => '/admin/featured-topics/'. $feature->id .'/delete','btnClass' => 'btn-sm','icon' => true])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $featuredTopics->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts_bottom')

@endpush
