@extends('admin.layouts.app')

@push('libraries_top')

@endpush

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{trans('admin/main.referral_history')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/">{{trans('admin/main.dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{trans('admin/main.referral_history')}}</div>
            </div>
        </div>

        <div class="section-body">

            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-people-arrows"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.total')}} {{ trans('admin/main.referred_users') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $affiliatesCount }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-user-tag"></i>
                        </div>

                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.total')}} {{ trans('admin/main.affiliate_users') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ $affiliateUsersCount }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-money-bill"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.total')}} {{ trans('admin/main.registeration_amount') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ addCurrencyToPrice(round($allAffiliateAmounts, 2)) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-money-bill-wave"></i></div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{trans('admin/main.total_commission_amount')}}</h4>
                            </div>
                            <div class="card-body">
                                {{ addCurrencyToPrice(round($allAffiliateCommissionAmounts, 2)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-header">
                            @can('admin_referrals_export')
                                <div class="text-right">
                                    <a href="/admin/referrals/excel?type=history" class="btn btn-primary">{{ trans('admin/main.export_xls') }}</a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped font-14 ">
                                    <tr>
                                        <th>{{ trans('admin/main.affiliate_user') }}</th>
                                        <th>{{ trans('admin/main.referred_user') }}</th>
                                        <th>{{ trans('admin/main.affiliate_registration_amount') }}</th>
                                        <th>{{ trans('admin/main.affiliate_user_commission') }}</th>
                                        <th>{{ trans('admin/main.referred_user_amount') }}</th>
                                        <th>{{ trans('admin/main.date') }}</th>
                                    </tr>

                                    <tbody>
                                    @foreach($affiliates as $affiliate)
                                        <tr>
                                            <td>{{ $affiliate->affiliateUser->full_name }}</td>

                                            <td>{{ $affiliate->referredUser->full_name }}</td>

                                            <td>{{ addCurrencyToPrice($affiliate->getAffiliateRegistrationAmountsOfEachReferral()) }}</td>

                                            <td>{{ addCurrencyToPrice($affiliate->getTotalAffiliateCommissionOfEachReferral()) }}</td>

                                            <td>{{ addCurrencyToPrice($affiliate->getReferredAmount()) }}</td>

                                            <td>{{ dateTimeFormat($affiliate->created_at, 'Y M j | H:i') }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $affiliates->appends(request()->input())->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="card">
        <div class="card-body">
            <div class="section-title ml-0 mt-0 mb-3"><h5>{{trans('admin/main.hints')}}</h5></div>
            <div class="row">
                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.total_user_hint')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.total_user_desc')}}</div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.total_affiliate_hint')}}</div>
                        <div class=" text-small font-600-bold">{{trans('admin/main.total_affiliate_desc')}}</div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.total_aff_amount_hint')}}</div>
                        <div class="text-small font-600-bold">{{trans('admin/main.total_aff_amount_desc')}}</div>
                    </div>
                </div>

                  <div class="col-md-3">
                    <div class="media-body">
                        <div class="text-primary mt-0 mb-1 font-weight-bold">{{trans('admin/main.total_aff_commission_hint')}}</div>
                        <div class="text-small font-600-bold">{{trans('admin/main.total_aff_commission_desc')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts_bottom')

@endpush
