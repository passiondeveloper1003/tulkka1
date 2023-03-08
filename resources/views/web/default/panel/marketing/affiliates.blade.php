@extends(getTemplate() . '.panel.layouts.panel_layout')

@push('styles_top')
@endpush

@section('content')
    <section class="">
        <h2 class="font-18 font-weight-normal">{{ trans('panel.affiliate_statistics') }}</h2>

        <div class="activities-container mt-15">
            <div class="py-30 pl-40 bg-white br-5">
                <div class="font-14 text-primary">
                    {{ trans('update.affilate_notification') }}        
            </div>
            </div>
            <div class="row row-40 mt-40">
                <div class="col-lg-3">
                    <div class="d-flex justify-content-center stats-card">
                        <div class="d-flex flex-column align-items-center text-center mt-40">
                            <img src="/assets/default/img/homeworks.svg" width="36" height="36" alt="">
                            <strong class="font-36 font-weight-bold mt-20">{{ $referredUsersCount }}</strong>
                            <span class="font-16 text-dark-blue text-gray mt-10">{{ trans('panel.referred_users') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class=" d-flex justify-content-center stats-card">
                        <div class="d-flex flex-column align-items-center text-center mt-40">
                            <img src="/assets/default/img/homeworks2.svg" width="36" height="36" alt="">
                            <strong
                                class="font-36 font-weight-bold mt-20">{{ addCurrencyToPrice(round($affiliateBonus, 2)) }}</strong>
                            <span class="font-16 text-dark-blue text-gray mt-10">{{ trans('panel.affiliate_bonus') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-60">
        <div class="row align-items-center">
            <div class="col-12">
                <h2 class="section-title">{{ trans('panel.affiliate_summary') }}</h2>

                @if (!empty($referralSettings))
                    <div class="mt-15 font-14 text-gray">
                        @if (!empty($referralSettings['affiliate_user_amount']))
                            <p>- {{ trans('panel.user_registration_reward') }}:
                                {{ addCurrencyToPrice($referralSettings['affiliate_user_amount']) }}</p>
                        @endif
                        @if (!empty($referralSettings['referred_user_amount']))
                            <p>- {{ trans('panel.referred_user_registration_reward') }}:
                                {{ addCurrencyToPrice($referralSettings['referred_user_amount']) }}</p>
                        @endif
                        @if (!empty($referralSettings['affiliate_user_commission']))
                            <p>- {{ trans('panel.referred_user_purchase_commission') }}:
                                {{ $referralSettings['affiliate_user_commission'] }}%</p>
                        @endif
                        <p>- {{ trans('panel.your_affiliate_code') }}: {{ $affiliateCode->code }}</p>
                        @if (!empty($referralSettings['referral_description']))
                            <p>- {{ trans('update.affilate_desc') }}</p>
                        @endif
                    </div>
                @endif
            </div>
            <div class="col-12 col-md-4 mt-30">
                <h3 class="font-16 font-weight-500">{{ trans('update.affilate_url') }}</h3>

                <div class="form-group mt-5">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text js-copy" data-input="affiliate_url" data-toggle="tooltip" data-placement="top" title="Copy" data-copy-text="Copy" data-done-text="Done">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy text-white"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                            </button>
                        </div>
                        <input type="text" name="affiliate_url" readonly="" value="http://localhost:8000/reff/738979" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-2 mt-30">
                <button class="btn btn-sm btn-primary w-100 mt-15">{{ trans('update.affilate_btn') }}</button>
            </div>
        </div>
        

        <!-- <div class="row mt-15">
            <div class="col-12 col-lg-5">
                <h3 class="font-16 font-weight-500">{{ trans('panel.affiliate_url') }}</h3>

                <div class="form-group mt-5">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="button" class="input-group-text js-copy" data-input="affiliate_url"
                                data-toggle="tooltip" data-placement="top" title="{{ trans('public.copy') }}"
                                data-copy-text="{{ trans('public.copy') }}" data-done-text="{{ trans('public.done') }}">
                                <i data-feather="copy" width="18" height="18" class="text-white"></i>
                            </button>
                        </div>
                        <input type="text" name="affiliate_url" readonly value="{{ $affiliateCode->getAffiliateUrl() }}"
                            class="form-control" />
                    </div>
                </div>
            </div>
        </div> -->

    </section>

    <section class="mt-25">
        <div class="row">
            <div class="col-10">
            <h2 class="font-18 font-weight-normal">{{ trans('panel.earnings') }}</h2>

<div class="panel-section-card py-20 px-25 mt-20">
    <div class="row">
        <div class="col-12 ">
            <div class="table-responsive">
                <table class="table text-center custom-table">
                    <thead>
                        <tr>
                            <th>{{ trans('panel.user') }}</th>
                            <th class="text-center">{{ trans('panel.affiliate_bonus') }}</th>
                            <th class="text-center">{{ trans('panel.registration_date') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($affiliates as $affiliate)
                            <tr>
                                <td class="text-left">
                                    <div class="user-inline-avatar d-flex align-items-center">
                                        <div class="avatar bg-gray200">
                                            <img src="{{ $affiliate->referredUser->getAvatar() }}"
                                                class="img-cover" alt="{{ $affiliate->referredUser->full_name }}">
                                        </div>
                                        <div class=" ml-5">
                                            <span
                                                class="d-block font-weight-500">{{ $affiliate->referredUser->full_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ addCurrencyToPrice($affiliate->getTotalAffiliateCommissionOfEachReferral()) }}
                                </td>

                                @php

                                    $date = dateTimeFormat($affiliate->created_at, 'Y M j | H:i');
                                    
                                    preg_match('/[a-zA-Z]+/', $date, $filter);
                                    $result = preg_replace('/[a-zA-Z]+/', trans('panel.' . $filter[0]), $date);
                                @endphp
                                <td>{{ $result }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="my-30">
                {{ $affiliates->appends(request()->input())->links('vendor.pagination.panel') }}
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
        
    </section>
@endsection
