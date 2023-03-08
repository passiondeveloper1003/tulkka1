@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('admin/main.categories') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('categories.categories') }}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('admin_store_categories_create')
                                <a href="/admin/store/categories/create" class="btn btn-primary">{{ trans('admin/main.add_new') }}</a>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14">
                                    <tr>
                                        <th>{{ trans('admin/main.icon') }}</th>
                                        <th class="text-left">{{ trans('admin/main.title') }}</th>
                                        <th>{{ trans('update.physical_products') }}</th>
                                        <th>{{ trans('update.virtual_products') }}</th>
                                        <th>{{ trans('admin/main.action') }}</th>
                                    </tr>
                                    @foreach($categories as $category)

                                        <tr>
                                            <td>
                                                <img src="{{ $category->icon }}" width="30" alt="">
                                            </td>
                                            <td class="text-left">{{ $category->title }}</td>
                                            <td>{{ $category->getSelfAndChideProductsCount(\App\Models\Product::$physical) }}</td>
                                            <td>{{ $category->getSelfAndChideProductsCount(\App\Models\Product::$virtual) }}</td>
                                            <td>
                                                @can('admin_store_categories_edit')
                                                    <a href="/admin/store/categories/{{ $category->id }}/edit"
                                                       class="btn-transparent btn-sm text-primary">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('admin_store_categories_delete')
                                                    @include('admin.includes.delete_button',['url' => '/admin/store/categories/'.$category->id.'/delete'])
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $categories->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
