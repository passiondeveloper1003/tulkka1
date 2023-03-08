@extends('admin.layouts.app')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ trans('update.registration_packages') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{ trans('update.registration_packages') }}</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('update.total_buy_instructors_packages') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalBuyInstructorsPackages }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-clipboard-check"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('update.total_buy_organization_packages') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $totalBuyOrganizationPackages }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-users"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('financial.total_amount') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ addCurrencyToPrice($sales->sum('total_amount')) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-users"></i></div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>{{ trans('admin/main.total_sales') }}</h4>
                        </div>
                        <div class="card-body">
                            {{ $sales->count() }}
                        </div>
                    </div>
                </div>
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
                                        <th class="text-left">{{ trans('admin/main.user') }}</th>
                                        <th class="text-center">{{ trans('public.user_role') }}</th>
                                        <th class="text-center">{{ trans('admin/main.title') }}</th>
                                        <th class="text-center">{{ trans('public.days') }}</th>
                                        <th class="text-center">{{ trans('admin/main.price') }}</th>
                                        <th class="text-center">{{ trans('update.activation_date') }}</th>
                                        <th class="text-center">{{ trans('admin/main.ext_date') }}</th>
                                    </tr>

                                    @foreach($sales as $sale)
                                        <tr>
                                            <td class="text-left">{{ !empty($sale->buyer) ? $sale->buyer->full_name : '' }}</td>
                                            <td class="text-center">{{ !empty($sale->buyer) ? $sale->buyer->role_name : '' }}</td>
                                            <td class="text-center">{{ !empty($sale->registrationPackage) ? $sale->registrationPackage->title : '' }}</td>
                                            <td class="text-center">{{ !empty($sale->registrationPackage) ? $sale->registrationPackage->days : '' }}</td>
                                            <td class="text-center">{{ !empty($sale->registrationPackage) ? addCurrencyToPrice($sale->registrationPackage->price) : '' }}</td>
                                            <td class="text-center">{{ dateTimeFormat($sale->created_at, 'Y M j | H:i') }}</td>
                                            <td class="text-center">{{ !empty($sale->registrationPackage) ? dateTimeFormat(($sale->created_at + ($sale->registrationPackage->days * 24 * 60 *60)) , 'Y M j | H:i') : '' }}</td>
                                        </tr>
                                    @endforeach

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $sales->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

