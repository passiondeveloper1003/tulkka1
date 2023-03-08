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
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('admin_store_specifications_create')
                                <a href="/admin/store/specifications/create" class="btn btn-primary">{{ trans('admin/main.add_new') }}</a>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('admin/main.type') }}</th>
                                        <th>{{ trans('admin/main.categories') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($specifications as $specification)

                                        <tr>
                                            <td class="text-left">{{ $specification->title }}</td>
                                            <td>{{ trans('update.'.$specification->input_type) }}</td>
                                            <td>{{ $specification->categories_count }}</td>
                                            <td>
                                                @can('admin_store_specifications_edit')
                                                    <a href="/admin/store/specifications/{{ $specification->id }}/edit"
                                                       class="btn-transparent btn-sm text-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('admin_store_specifications_delete')
                                                    @include('admin.includes.delete_button',['url' => '/admin/store/specifications/'.$specification->id.'/delete'])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $specifications->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
