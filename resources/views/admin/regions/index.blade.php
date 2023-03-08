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

                    @can('admin_regions_create')
                        <div class="text-right">
                            <a href="/admin/regions/new?type={{ $type }}" class="btn btn-primary">{{ trans('admin/main.new') }}</a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped text-center font-14">

                            <tr>
                                <th class="text-left">{{ trans('admin/main.title') }}</th>

                                @if($type == \App\Models\Region::$country)
                                    <th class="text-center">{{ trans('update.provinces') }}</th>
                                @elseif($type == \App\Models\Region::$province)
                                    <th class="text-center">{{ trans('update.country') }}</th>
                                    <th class="text-center">{{ trans('update.cities') }}</th>
                                @elseif($type == \App\Models\Region::$city)
                                    <th class="text-center">{{ trans('update.country') }}</th>
                                    <th class="text-center">{{ trans('update.province') }}</th>
                                @elseif($type == \App\Models\Region::$district)
                                    <th class="text-center">{{ trans('update.country') }}</th>
                                    <th class="text-center">{{ trans('update.province') }}</th>
                                    <th class="text-center">{{ trans('update.city') }}</th>
                                @endif

                                <th class="text-center">{{ trans('admin/main.instructor') }}</th>
                                <th class="text-center">{{ trans('admin/main.date') }}</th>
                                <th class="text-center">{{ trans('admin/main.actions') }}</th>
                            </tr>

                            @foreach($regions as $region)

                                <tr>
                                    <td>{{ $region->title }}</td>

                                    @if($type == \App\Models\Region::$country)
                                        <td>{{ $region->countryProvinces->count() }}</td>

                                        <td>{{ $region->countryUsers->count() }}</td>
                                    @elseif($type == \App\Models\Region::$province)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->provinceCities->count() }}</td>

                                        <td>{{ $region->provinceUsers->count() }}</td>
                                    @elseif($type == \App\Models\Region::$city)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->province->title }}</td>
                                        <td>{{ $region->cityUsers->count() }}</td>
                                    @elseif($type == \App\Models\Region::$district)
                                        <td>{{ $region->country->title }}</td>
                                        <td>{{ $region->province->title }}</td>
                                        <td>{{ $region->city->title }}</td>
                                        <td>{{ $region->districtUsers->count() }}</td>
                                    @endif

                                    <td>{{ dateTimeFormat($region->created_at, 'Y M j | H:i') }}</td>

                                    <td>
                                        @can('admin_regions_edit')
                                            <a href="/admin/regions/{{ $region->id }}/edit" class="btn-transparent text-primary mr-2">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('admin_regions_delete')
                                            @include('admin.includes.delete_button',['url' => '/admin/regions/'.$region->id.'/delete'])
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="card-footer text-center">
                    {{ $regions->appends(request()->input())->links() }}
                </div>
            </section>
        </div>
    </section>
@endsection
