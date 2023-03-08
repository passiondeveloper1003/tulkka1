@extends(getTemplate() . '.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/chartjs/chart.min.css" />
    <link rel="stylesheet" href="/assets/default/vendors/apexcharts/apexcharts.css" />
@endpush

@section('content')
    <section class="dashboard-page">
        <!-- <div class="d-flex align-items-start justify-content-between flex-column flex-md-column"> -->
            <h1 class="panel-welcome text-left">{{ trans('panel.hi') }} {{ $authUser->full_name }}, {{ trans('panel.welcome_back') }}
            </h1>
            <div class="row row-40 mt-0 mt-md-40">
                <div class="col-12 col-md-6 col-lg-4 mb-20 mb-md-40">
                    <div class="stats-card p-20 text-center">
                            <div class="font-20 mt-10">{{ trans('update.remained_lessons') }}</div>
                            <div class="d-flex justify-content-between align-items-center mt-30">
                                <span class="font-18">English Class</span>
                                <div class="br-5 border border-primary font-12 dashboard-timebox">
                                    <div>Mon</div>
                                    <span>12 Pm</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-20">
                                <span class="font-18">English Class</span>
                                <div class="br-5 border border-primary font-12 dashboard-timebox">
                                    <div>Thu</div>
                                    <span>10 Am</span>
                                </div>
                            </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4 mb-20 mb-md-40">
                    <div class=" d-flex justify-content-center p-20 stats-card">
                        <div class="d-flex flex-column align-items-center text-center">
                            <span class="font-20 mt-10">{{ trans('update.finished_lessons') }}</span>
                            <strong
                                class="font-60 font-weight-normal mt-40">{{ $authUser->weeklyFinishedClasses() ?? 0 }}</strong>
                            <!-- <a class="btn btn-sm btn-primary rounded mt-4">{{ trans('update.view_schedule') }}<i
                                    class="fa-solid fa-calendar-days ml-2"></i></a> -->
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4 mb-20 mb-md-40">
                    <div class="d-flex align-items-start justify-content-center stats-card p-20">
                        <div class="d-flex flex-column align-items-center text-center">
                            <span class="font-20 mt-10">{{ trans('update.your_plan') }}</span>
                            @if ($authUser->subscription_type)
                                <strong
                                    class="font-20 text-dark-blue font-weight-bold mt-10">{{ $authUser->subscription_type }}</strong>
                                <span class="font-12 mt-2"> {{ trans('update.paid_upto') }}
                                    {{ $authUser->subscriptionDetails ? $authUser->subscriptionDetails->renew_date : '' }}</span>
                                <ul class="mt-25 font-14 dashboard-ul">
                                    <li>
                                        <span>
                                            {{ $authUser->subscriptionDetails ? $authUser->subscriptionDetails->each_lesson : '' }}
                                        </span>
                                    </li>
                                    <li class="mt-1">
                                        <span>
                                            {{ $authUser->subscriptionDetails ? $authUser->subscriptionDetails->how_often : '' }}
                                        </span>
                                    </li>
                                </ul>
                                @if ((isset($authUser) && !$authUser->isTeacher()) || !isset($authUser))
                                    <!-- <li class="rounded text-nowrap"> -->
                                    <a @if (isset($authUser) && !$authUser->isTeacher() || !isset($authUser)) onclick='Livewire.emit("showModal","true")' @endif
                                        class="mt-20 py-1 px-15 font-14 border-primary rounded text-primary bg-hover-primary">Update Plan</a>
                                    <!-- </li> -->
                                @endif
                            @else
                                <strong
                                    class="font-20 text-dark-blue font-weight-bold mt-10">{{ trans('update.trial') }}</strong>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            @if (!$authUser->isUser())
                <div
                    class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                    <label class="mb-0 mr-10 cursor-pointer text-gray font-14 font-weight-500"
                        for="iNotAvailable">{{ trans('panel.i_not_available') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="disabled" @if ($authUser->offline) checked @endif
                            class="custom-control-input" id="iNotAvailable">
                        <label class="custom-control-label" for="iNotAvailable"></label>
                    </div>
                </div>
            @endif
        <!-- </div> -->

        @if (!$authUser->financial_approval and !$authUser->isUser())
            <div class="p-15 mt-20 p-lg-20 not-verified-alert font-weight-500 text-dark-blue rounded-sm panel-shadow">
                {{ trans('panel.not_verified_alert') }}
                <a href="/panel/setting/step/7" class="text-decoration-underline">{{ trans('panel.this_link') }}</a>.
            </div>
        @endif

        @if (!$authUser->isUser())
            <div class="p-15 mt-20 p-lg-20 not-verified-alert font-weight-500 text-dark-blue rounded-sm panel-shadow">
                <span>{{ trans('public.dont_forget') }}</span>
            </div>
        @endif

        {{-- <div style="min-height: 350px;"
            class="bg-white dashboard-banner-container position-relative px-15 px-ld-35 py-10 panel-shadow rounded-sm p-20">
            <h2 class="font-30 text-primary line-height-1 ">
                <span class="d-block">{{ trans('panel.hi') }} {{ $authUser->full_name }},</span>
                @if (!$authUser->isTeacher())
                    @if ($authUser->isPaidUser())
                        <img src="{{ $subscription->icon }}" class="img-contain mt-4" alt="">
                        <div class="font-16 text-secondary font-weight-bold mt-2">{{ trans('update.your_subs') }}
                            {{ $authUser->subscription_type }}</div>
                        <div class="plan-icon">
                        </div>
                    @endif

                    @if ($authUser->isWeeklyPackageUsed())
                        <div class="font-14 text-danger font-weight-bold mt-2">{{ trans('update.finished_pack') }}
                        </div>
                        <div class="font-14 text-danger font-weight-bold mt-4">{{ trans('update.recalc') }}</div>
                    @elseif($authUser->isPaidUser())
                        <div class="font-14 text-primary font-weight-bold mt-4">You have finished
                            {{ $authUser->weeklyFinishedClasses() }}/{{ $authUser->weeklyTotalClasses() }} classes in
                            this week</div>
                        <div class="font-14 text-primary font-weight-bold mt-4">{{ trans('update.recalc') }}</div>
                    @elseif(!$authUser->isPaidUser() && !$authUser->isTeacher())
                        <div class="font-14 text-secondary font-weight-bold mt-4">{{ trans('public.no_active_lesson') }}
                        </div>
                        <button onclick='Livewire.emit("showModal","SomeData")'
                            class="btn btn-sm btn-primary mt-2">{{ trans('public.subs_now') }}</button>
                    @endif
                @endif
            </h2>


            <div class="dashboard-banner">
                <img src="{{ getPageBackgroundSettings('dashboard') }}" alt="" class="img-cover">
            </div>
        </div> --}}
    </section>

    <section class="dashboard">
        <div class="row d-none">
            {{-- <div class="col-12 col-lg-3 mt-35">
                <div class="bg-white account-balance rounded-sm panel-shadow py-15 py-md-30 px-10 px-md-20">
                    <div class="text-center">
                        <img src="/assets/default/img/activity/36.svg" class="account-balance-icon" alt="">

                        <h3 class="font-16 font-weight-500 text-gray mt-25">{{ trans('panel.account_balance') }}</h3>
                        <span class="mt-5 d-block font-30 text-secondary">{{ addCurrencyToPrice($authUser->getAccountingBalance()) }}</span>
                    </div>

                    @php
                        $getFinancialSettings = getFinancialSettings();
                        $drawable = $authUser->getPayout();
                        $can_drawable = ($drawable > ((!empty($getFinancialSettings) and !empty($getFinancialSettings['minimum_payout'])) ? (int)$getFinancialSettings['minimum_payout'] : 0))
                    @endphp

                    <div class="mt-20 pt-30 border-top border-gray300 d-flex align-items-center @if ($can_drawable) justify-content-between @else justify-content-center @endif">
                        @if ($can_drawable)
                            <span class="font-16 font-weight-500 text-gray">{{ trans('panel.with_drawable') }}:</span>
                            <span class="font-16 font-weight-bold text-secondary">{{ addCurrencyToPrice($drawable) }}</span>
                        @else
                            <a href="/panel/financial/account" class="font-16 font-weight-bold text-dark-blue">{{ trans('financial.charge_account') }}</a>
                        @endif
                    </div>
                </div>
            </div> --}}

            <div class="col-12 col-lg-6 mt-35">
                <a href="@if ($authUser->isUser()) /panel @else /panel/meetings/requests @endif"
                    class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center">
                    <div class="stat-icon requests">
                        <img src="/assets/default/img/icons/request.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span
                            class="font-30 text-secondary">{{ $authUser->isUser() ? $authUser->weeklyRemainedClasses() : $authUser->upcomingClasses()->count() }}</span>
                        <span
                            class="font-16 text-gray font-weight-500">{{ $authUser->isUser() ? trans('panel.left_classes') : trans('panel.pending_appointments') }}</span>
                    </div>
                </a>

                <a href="@if ($authUser->isUser()) /panel/meetings/reservation @else /panel/financial/sales @endif"
                    class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center mt-15 mt-md-30">
                    <div class="stat-icon monthly-sales">
                        <img src="@if ($authUser->isUser()) /assets/default/img/icons/meeting.svg @else /assets/default/img/icons/request.svg @endif"
                            alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span
                            class="font-30 text-secondary">{{ $authUser->isTeacher() ? $dailyCompletedLessons : $authUser->weeklyTotalClasses() }}</span>
                        <span
                            class="font-16 text-gray font-weight-500">{{ $authUser->isTeacher() ? trans('panel.daily_comp_lessons') : trans('panel.total_comp_classes_week') }}</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-lg-6 mt-35">
                <a href="/panel/support"
                    class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center">
                    <div class="stat-icon support-messages">

                        <img src="/assets/default/img/icons/comment.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span class="font-30 text-secondary">{{ $authUser->recivedUnreadedMessages()->count() }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ trans('panel.unreaded_messages') }}</span>
                    </div>
                </a>

                <a href="@if ($authUser->isUser()) /panel/feedbacks @else /panel/feedbacks @endif"
                    class="dashboard-stats rounded-sm panel-shadow p-10 p-md-20 d-flex align-items-center mt-15 mt-md-30">
                    <div class="stat-icon comments">
                        <img src="/assets/default/img/icons/support.svg" alt="">
                    </div>
                    <div class="d-flex flex-column ml-15">
                        <span
                            class="font-30 text-secondary">{{ !empty($commentsCount) ? $commentsCount : $authUser->feedbacksFromTeacher()->count() }}</span>
                        <span class="font-16 text-gray font-weight-500">{{ trans('panel.student_feedbacks') }}</span>
                    </div>
                </a>
            </div>

            {{-- <div class="col-12 col-lg-3 mt-35">
                <div class="bg-white account-balance rounded-sm panel-shadow py-15 py-md-15 px-10 px-md-20">
                    <div data-percent="{{ !empty($nextBadge) ? $nextBadge['percent'] : 0 }}"
                        data-label="{{ (!empty($nextBadge) and !empty($nextBadge['earned'])) ? $nextBadge['earned']->title : '' }}"
                        id="nextBadgeChart" class="text-center">
                    </div>
                    <div class="mt-10 pt-10 border-top border-gray300 d-flex align-items-center justify-content-between">
                        <span class="font-16 font-weight-500 text-gray">{{ trans('panel.next_badge') }}:</span>
                        <span
                            class="font-16 font-weight-bold text-secondary">{{ (!empty($nextBadge) and !empty($nextBadge['badge'])) ? $nextBadge['badge']->title : trans('public.not_defined') }}</span>
                    </div>
                </div>
            </div> --}}
        </div>

        <div class="row">
            {{-- @if (!$authUser->isTeacher())
                <div class="col-12 col-lg-6 mt-35">

                    <div class="bg-white noticeboard rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30">
                        <h3 class="font-16 text-dark-blue font-weight-bold">{{ trans('panel.noticeboard') }}</h3>

                        @foreach ($authUser->getUnreadNoticeboards() as $getUnreadNoticeboard)
                            <div class="noticeboard-item py-15">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h4 class="js-noticeboard-title font-weight-500 text-secondary">
                                            {!! truncate($getUnreadNoticeboard->title, 150) !!}</h4>
                                        <div class="font-12 text-gray mt-5">
                                            <span class="mr-5">{{ trans('public.created_by') }}
                                                {{ $getUnreadNoticeboard->sender }}</span>
                                            |
                                            <span
                                                class="js-noticeboard-time ml-5">{{ dateTimeFormat($getUnreadNoticeboard->created_at, 'j M Y | H:i') }}</span>
                                        </div>
                                    </div>

                                    <div>
                                        <button type="button" data-id="{{ $getUnreadNoticeboard->id }}"
                                            class="js-noticeboard-info btn btn-sm btn-border-white">{{ trans('panel.more_info') }}</button>
                                        <input type="hidden" class="js-noticeboard-message"
                                            value="{{ $getUnreadNoticeboard->message }}">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
            @endif --}}

            <div class="col-12">
                @if ($authUser->isTeacher())
                    @livewire('dashboard-calendar', ['instructor' => $authUser])
                @endif
                @if (!$authUser->isTeacher() && $authUser->lessonsForStudent()->count() > 0)
                    @livewire('calendar', ['instructor' => $authUser->lastTeacher(), 'dashboard' => true])
                @endif

            </div>
            {{-- <div class="col-12 col-lg-6 mt-35">
                <div class="bg-white monthly-sales-card rounded-sm panel-shadow py-10 py-md-20 px-15 px-md-30">
                    <div class="d-flex align-items-center justify-content-between">
                        <h3 class="font-16 text-dark-blue font-weight-bold">
                            {{ $authUser->isUser() ? trans('panel.learning_statistics') : trans('panel.monthly_sales') }}
                        </h3>

                        <span class="font-16 font-weight-500 text-gray">{{ dateTimeFormat(time(), 'M Y') }}</span>
                    </div>

                    <div class="monthly-sales-chart">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div> --}}
        </div>
    </section>


    <div class="d-none" id="iNotAvailableModal">
        <div class="offline-modal">
            <h3 class="section-title after-line">{{ trans('panel.offline_title') }}</h3>
            <p class="mt-20 font-16 text-gray">{{ trans('panel.offline_hint') }}</p>

            <div class="form-group mt-15">
                <label>{{ trans('panel.offline_message') }}</label>
                <textarea name="message" rows="4" class="form-control ">{{ $authUser->offline_message }}</textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mt-30 d-flex align-items-center justify-content-end">
                <button type="button"
                    class="js-save-offline-toggle btn btn-primary btn-sm">{{ trans('public.save') }}</button>
                <button type="button" class="btn btn-danger ml-10 close-swl btn-sm">{{ trans('public.close') }}</button>
            </div>
        </div>
    </div>

    <div class="d-none" id="noticeboardMessageModal">
        <div class="text-center">
            <h3 class="modal-title font-20 font-weight-500 text-dark-blue"></h3>
            <span class="modal-time d-block font-12 text-gray mt-25"></span>
            <p class="modal-message font-weight-500 text-gray mt-4"></p>
        </div>
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/apexcharts/apexcharts.min.js"></script>
    <script src="/assets/default/vendors/chartjs/chart.min.js"></script>

    <script>
        var offlineSuccess = '{{ trans('panel.offline_success') }}';
        var $chartDataMonths = @json($monthlyChart['months']);
        var $chartData = @json($monthlyChart['data']);
    </script>

    <script src="/assets/default/js/panel/dashboard.min.js"></script>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.on('timeEnabled', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Selected Time Enabled',
                    position: 'topRight'
                });
            });
            Livewire.on('alreadyEnabled', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Selected Time is Already Enabled',
                    position: 'topRight'
                });
            });
            Livewire.on('timeDisabled', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Selected Time Disabled',
                    position: 'topRight'
                });
            });
            Livewire.on('timeAlreadyDisabled', postId => {
                iziToast.success({
                    title: 'Success',
                    message: 'Selected Time is Already Disabled',
                    position: 'topRight'
                });
            });
        })
    </script>
    @include('web.default.includes.booking_actions')
@endpush
