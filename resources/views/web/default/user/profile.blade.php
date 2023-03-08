@extends(getTemplate() . '.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/css/css-stars.css">
    <link rel="stylesheet" href="/assets/default/vendors/video/video-js.min.css">
@endpush


@section('content')
    <div class="bg-tgray">
        <section
            class="container  course-cover-container {{ empty($activeSpecialOffer) ? 'not-active-special-offer' : '' }} d-flex align-items-center profile-info">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex">
                        <img src="{{ $user->getAvatar(50) }}" class="profile-img rounded-circle" alt="">
                        <div class="d-flex flex-column ml-2">
                            <h3 class="font-20 text-tblack d-block">{{ $user->full_name }}</h3>
                            @if ($user->occupations->count() > 0)
                                <div class="font-12 mt-2"><i
                                        class="fa-solid fa-person-chalkboard mx-2 text-primary"></i>{{ trans('site.teaching') }}
                                </div>
                                <div class="d-flex flex-row align-items-center">

                                    @foreach ($user->occupations as $occupation)
                                        @if ($occupation->type == 'language')
                                            <span
                                                class="badge rounded-pill badge-primary @if (!$loop->first) m-1 @endif">{{ trans('site.' . $occupation->category->title) }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="custom-star position-absolute right-0"><img src="/assets/default/img/star.svg"
                            alt="" /></div>
                    <div class="mt-20 font-14">
                        @if (isset($user->about))
                            <div class="mt-20">
                                <div class="mt-15 teacher-description" style="text-align: left;">
                                    {!! truncate(clean($user->about), 150) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($user->occupations->count() > 0)
                    <div class="col-12 mt-2">
                        <div class="d-flex flex-row  align-items-center">
                            <div class="font-12"><i
                                    class="fa-solid fa-language mx-2 text-primary"></i>{{ trans('site.also_speaking') }}
                            </div>
                            @foreach ($user->occupations as $occupation)
                                @if ($occupation->type == 'also_speaking')
                                    <span
                                        class="badge rounded-pill badge-primary m-1">{{ trans('site.' . $occupation->category->title) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="col-6 mt-2">
                    <div class="d-flex align-items-center">
                        @include('web.default.includes.webinar.rate', [
                            'rate' => $user->getInstructorRate(),
                        ])
                        <span class="ml-10 font-14">({{ $user->instructorReviews->pluck('creator_id')->count() }}
                            {{ trans('public.ratings') }})</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="container course-content-section text_lesson pb-100">
        <div class="row">
            <div class="col-12 col-lg-8 profile-section">
                <div class="course-content-body user-select-none">
                    <div class="mt-50 mt-md-50">
                        <ul class="nav nav-tabs  rounded-sm py-15 d-flex align-items-center" id="tabs-tab" role="tablist">
                            <li class="nav-item rounded ">
                                <a class="p-2 rounded position-relative font-14 text-gray {{ (empty(request()->get('tab', '')) or request()->get('tab', '') == 'calendar') ? 'active' : '' }}"
                                    id="calendar-tab" data-toggle="tab" href="#calendar" role="tab"
                                    aria-controls="calendar" aria-selected="true"><i
                                        class="fa-regular fa-calendar mx-2"></i>{{ trans('product.calendar') }}</a>
                            </li>
                            <li class="nav-item rounded">
                                <a class="p-2 rounded position-relative font-14 text-gray {{ request()->get('tab', '') == 'information' ? 'active' : '' }}"
                                    id="information-tab" data-toggle="tab" href="#information" role="tab"
                                    aria-controls="information" aria-selected="false"><i
                                        class="fa-solid fa-circle-info mx-2"></i>{{ trans('product.information') }}</a>
                            </li>
                            <li class="nav-item rounded ">
                                <a class="p-2 rounded position-relative font-14 text-gray {{ request()->get('tab', '') == 'reviews' ? 'active' : '' }}"
                                    id="reviews-tab" data-toggle="tab" href="#reviews" role="tab"
                                    aria-controls="reviews" aria-selected="false"> <i class="fa-solid fa-comment mr-2"></i>
                                    {{ trans('product.reviews') }}
                                    ({{ $user->instructorReviews()->count() > 0 ? $user->instructorReviews()->count() : 0 }})</a>
                            </li>
                        </ul>

                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade {{ (empty(request()->get('tab', '')) or request()->get('tab', '') == 'calendar') ? 'show active' : '' }}"
                                id="calendar" role="tabpanel" aria-labelledby="calendar-tab">
                                @livewire('calendar', ['instructor' => $user, 'calledFrom' => 'profile'])
                            </div>
                            <div class="tab-pane fade {{ request()->get('tab', '') == 'information' ? 'show active' : '' }}"
                                id="information" role="tabpanel" aria-labelledby="information-tab">
                                @include(getTemplate() . '.user.profile_tabs.information')
                            </div>
                            <div class="tab-pane fade {{ request()->get('tab', '') == 'reviews' ? 'show active' : '' }}"
                                id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                                @include(getTemplate() . '.user.profile_tabs.reviews')

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="course-content-sidebar col-12 col-lg-4 mt-50 mt-lg-0">
                <div class="rounded-lg shadow-sm">
                    <div class="profil-img course-img {{ $user->video_demo ? 'has-video' : '' }}"
                        @if ($user->video_demo) onclick='Livewire.emit("showVideoModal","{{ $user->full_name }} Video", "{{ $user->video_demo }}")' @endif>
                        <div class="custom-dots-profile position-absolute right-0"><img
                                src="/assets/default/img/dots.png" /></div>
                        <div class="p-2 img-cover"><img src="{{'/store/'.$user->video_demo_thumb}}" class="img-cover "
                                alt=""></div>


                        @if ($user->video_demo)
                            <div class="course-video-icon cursor-pointer d-flex align-items-center justify-content-center">
                                <i data-feather="play" width="25" height="25"></i>
                            </div>
                        @endif
                    </div>

                    <div class="px-20 pb-30">
                        <form action="/cart/store" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="item_id" value="{{ $user->id }}">
                            <input type="hidden" name="item_name" value="user_id">

                            @if (isset($course) && !empty($course->tickets))
                                @foreach ($course->tickets as $ticket)
                                    <div class="form-check mt-20">
                                        <input class="form-check-input" @if (!$ticket->isValid()) disabled @endif
                                            type="radio" data-discount="{{ $ticket->discount }}"
                                            value="{{ $ticket->isValid() ? $ticket->id : '' }}" name="ticket_id"
                                            id="courseOff{{ $ticket->id }}">
                                        <label class="form-check-label d-flex flex-column cursor-pointer"
                                            for="courseOff{{ $ticket->id }}">
                                            <span class="font-16 font-weight-500 text-dark-blue">{{ $ticket->title }}
                                                @if (!empty($ticket->discount))
                                                    ({{ $ticket->discount }}% {{ trans('public.off') }})
                                                @endif
                                            </span>
                                            <span class="font-14 text-gray">{{ $ticket->getSubTitle() }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif

                            <div class="d-flex align-items-center justify-content-center mt-20">
                                <span class="font-24 text-secondary">{{ $user->full_name }}</span>
                            </div>



                            <div class="mt-20 d-flex flex-column">
                                <a data-toggle="tab" href="#calendar" role="tab" aria-controls="calendar"
                                    class="btn btn-primary"><i class="fa-solid fa-bolt mx-2 text-warning"></i>
                                    {{ trans('update.book_now') }}</a>
                                <a data-toggle="tab" href="#calendar" role="tab" aria-controls="calendar"
                                    class="btn btn-white text-primary mt-2 border-primary rounded"><i
                                        class="fa-solid fa-message text-primary mx-2"></i>
                                    {{ trans('update.send_message') }}</a>
                                <a data-toggle="tab" href="#calendar" role="tab" aria-controls="calendar"
                                    class="btn btn-white text-primary mt-2 border-primary rounded"><i
                                        class="fa-solid fa-share-nodes text-primary mx-2"></i>
                                    {{ trans('public.share') }}</a>
                                @if (isset($hasBought) && $hasBought)
                                @elseif(isset($course) && $course->price > 0)
                                    <button type="button"
                                        class="btn btn-primary {{ $canSale ? 'js-course-add-to-cart-btn' : $course->cantSaleStatus($hasBought) . ' disabled ' }}">
                                        @if (!$canSale)
                                            {{ trans('update.disabled_add_to_cart') }}
                                        @else
                                            {{ trans('public.add_to_cart') }}
                                        @endif
                                    </button>


                                    @if ($canSale and !empty($course->points))
                                        <a href="{{ !auth()->check() ? '/login' : '#' }}"
                                            class="{{ auth()->check() ? 'js-buy-with-point' : '' }} btn btn-outline-warning mt-20 {{ !$canSale ? 'disabled' : '' }}"
                                            rel="nofollow">
                                            {!! trans('update.buy_with_n_points', ['points' => $course->points]) !!}
                                        </a>
                                    @endif

                                    @if ($canSale and !empty(getFeaturesSettings('direct_classes_payment_button_status')))
                                        <button type="button"
                                            class="btn btn-outline-danger mt-20 js-course-direct-payment">
                                            {{ trans('update.buy_now') }}
                                        </button>
                                    @endif
                                @else
                                    {{-- <a href="{{ $canSale ? '/course/'. $course->slug .'/free' : '#' }}" class="btn btn-primary {{ (!$canSale) ? (' disabled ' . $course->cantSaleStatus($hasBought)) : '' }}">{{ trans('public.enroll_on_webinar') }}</a> --}}
                                @endif
                            </div>

                        </form>

                        @if (
                            !empty(getOthersPersonalizationSettings('show_guarantee_text')) and
                                getOthersPersonalizationSettings('show_guarantee_text'))
                            <div class="mt-20 d-flex align-items-center justify-content-center text-gray">
                                <i data-feather="thumbs-up" width="20" height="20"></i>
                                <span class="ml-5 font-14">{{ getOthersPersonalizationSettings('guarantee_text') }}</span>
                            </div>
                        @endif

                        <div class="mt-35">
                            {{-- <strong class="d-block text-secondary font-weight-bold">{{ trans('webinars.this_webinar_includes',['classes' => trans('webinars.'.$course->type)]) }}</strong> --}}
                            {{--             @if ($course->isDownloadable())
                                <div class="mt-20 d-flex align-items-center text-gray">
                                    <i data-feather="download-cloud" width="20" height="20"></i>
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('webinars.downloadable_content') }}</span>
                                </div>
                            @endif

                            @if ($course->quizzes->where('certificate', 1)->count() > 0)
                                <div class="mt-20 d-flex align-items-center text-gray">
                                    <i data-feather="award" width="20" height="20"></i>
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('webinars.official_certificate') }}</span>
                                </div>
                            @endif

                            @if ($course->quizzes->where('status', \App\models\Quiz::ACTIVE)->count() > 0)
                                <div class="mt-20 d-flex align-items-center text-gray">
                                    <i data-feather="file-text" width="20" height="20"></i>
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('webinars.online_quizzes_count',['quiz_count' => $course->quizzes->where('status', \App\models\Quiz::ACTIVE)->count()]) }}</span>
                                </div>
                            @endif

                            @if ($course->support)
                                <div class="mt-20 d-flex align-items-center text-gray">
                                    <i data-feather="headphones" width="20" height="20"></i>
                                    <span class="ml-5 font-14 font-weight-500">{{ trans('webinars.instructor_support') }}</span>
                                </div>
                            @endif --}}
                        </div>



                        <div class="mt-30 text-center">
                            <button type="button" id="webinarReportBtn"
                                class="font-14 text-gray btn-transparent">{{ trans('webinars.report_this_teacher') }}</button>
                        </div>
                    </div>
                </div>

                {{-- @if ($course->teacher->offline)
                    <div class="rounded-lg shadow-sm mt-35 d-flex">
                        <div class="offline-icon offline-icon-left d-flex align-items-stretch">
                            <div class="d-flex align-items-center">
                                <img src="/assets/default/img/profile/time-icon.png" alt="offline">
                            </div>
                        </div>

                        <div class="p-15">
                            <h3 class="font-16 text-dark-blue">{{ trans('public.instructor_is_not_available') }}</h3>
                            <p class="font-14 font-weight-500 text-gray mt-15">{{ $course->teacher->offline_message }}</p>
                        </div>
                    </div>
                @endif --}}



                {{-- organization --}}
                {{-- @if ($course->creator_id != $course->teacher_id)
                    @include('web.default.course.sidebar_instructor_profile', ['courseTeacher' => $course->creator])
                @endif --}}
                {{-- teacher --}}
                {{-- @include('web.default.course.sidebar_instructor_profile', ['courseTeacher' => $course->teacher]) --}}

                {{--   @if ($course->webinarPartnerTeacher->count() > 0)
                    @foreach ($course->webinarPartnerTeacher as $webinarPartnerTeacher)
                        @include('web.default.course.sidebar_instructor_profile', ['courseTeacher' => $webinarPartnerTeacher->teacher])
                    @endforeach
                @endif --}}

                {{-- ./ teacher --}}

                {{-- tags --}}
                {{--      @if ($course->tags->count() > 0)
                    <div class="rounded-lg tags-card shadow-sm mt-35 px-25 py-20">
                        <h3 class="sidebar-title font-16 text-secondary font-weight-bold">{{ trans('public.tags') }}</h3>

                        <div class="d-flex flex-wrap mt-10">
                            @foreach ($course->tags as $tag)
                                <a href="" class="tag-item bg-gray200 p-5 font-14 text-gray font-weight-500 rounded">{{ $tag->title }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif --}}
                {{-- ads --}}
                @if (!empty($advertisingBannersSidebar) and count($advertisingBannersSidebar))
                    <div class="row">
                        @foreach ($advertisingBannersSidebar as $sidebarBanner)
                            <div class="rounded-lg sidebar-ads mt-35 col-{{ $sidebarBanner->size }}">
                                <a href="{{ $sidebarBanner->link }}">
                                    <img src="{{ $sidebarBanner->image }}" class="img-cover rounded-lg"
                                        alt="{{ $sidebarBanner->title }}">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Ads Bannaer --}}
        @if (!empty($advertisingBanners) and count($advertisingBanners))
            <div class="mt-30 mt-md-50">
                <div class="row">
                    @foreach ($advertisingBanners as $banner)
                        <div class="col-{{ $banner->size }}">
                            <a href="{{ $banner->link }}">
                                <img src="{{ $banner->image }}" class="img-cover rounded-sm"
                                    alt="{{ $banner->title }}">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        {{-- ./ Ads Bannaer --}}
    </section>


    <div id="webinarReportModal" class="d-none">
        <h3 class="section-title after-line font-20 text-dark-blue">{{ trans('product.report_the_course') }}</h3>

        {{-- <form action="/course/{{ $course->id }}/report" method="post" class="mt-25">

            <div class="form-group">
                <label class="text-dark-blue font-14">{{ trans('product.reason') }}</label>
                <select id="reason" name="reason" class="form-control">
                    <option value="" selected disabled>{{ trans('product.select_reason') }}</option>

                    @foreach (getReportReasons() as $reason)
                        <option value="{{ $reason }}">{{ $reason }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="form-group">
                <label class="text-dark-blue font-14" for="message_to_reviewer">{{ trans('public.message_to_reviewer') }}</label>
                <textarea name="message" id="message_to_reviewer" class="form-control" rows="10"></textarea>
                <div class="invalid-feedback"></div>
            </div>
            <p class="text-gray font-16">{{ trans('product.report_modal_hint') }}</p>

            <div class="mt-30 d-flex align-items-center justify-content-end">
                <button type="button" class="js-course-report-submit btn btn-sm btn-primary">{{ trans('panel.report') }}</button>
                <button type="button" class="btn btn-sm btn-danger ml-10 close-swl">{{ trans('public.close') }}</button>
            </div>
        </form> --}}
    </div>

    {{-- @include('web.default.course.share_modal')
    @include('web.default.course.buy_with_point_modal') --}}
@endsection

@push('scripts_bottom')
    <script src="/assets/default/js/parts/time-counter-down.min.js"></script>
    <script src="/assets/default/vendors/barrating/jquery.barrating.min.js"></script>
    <script src="/assets/default/vendors/video/video.min.js"></script>
    <script src="/assets/default/vendors/video/youtube.min.js"></script>
    <script src="/assets/default/vendors/video/vimeo.js"></script>

    <script>
        var webinarDemoLang = '{{ $user->full_name . ' Demonstration Video' }}';
        var replyLang = '{{ trans('panel.reply') }}';
        var closeLang = '{{ trans('public.close') }}';
        var saveLang = '{{ trans('public.save') }}';
        var reportLang = '{{ trans('panel.report') }}';
        var reportSuccessLang = '{{ trans('panel.report_success') }}';
        var reportFailLang = '{{ trans('panel.report_fail') }}';
        var messageToReviewerLang = '{{ trans('public.message_to_reviewer') }}';
        var copyLang = '{{ trans('public.copy') }}';
        var copiedLang = '{{ trans('public.copied') }}';
        var learningToggleLangSuccess = '{{ trans('public.course_learning_change_status_success') }}';
        var learningToggleLangError = '{{ trans('public.course_learning_change_status_error') }}';
        var notLoginToastTitleLang = '{{ trans('public.not_login_toast_lang') }}';
        var notLoginToastMsgLang = '{{ trans('public.not_login_toast_msg_lang') }}';
        var notAccessToastTitleLang = '{{ trans('public.not_access_toast_lang') }}';
        var notAccessToastMsgLang = '{{ trans('public.not_access_toast_msg_lang') }}';
        var canNotTryAgainQuizToastTitleLang = '{{ trans('public.can_not_try_again_quiz_toast_lang') }}';
        var canNotTryAgainQuizToastMsgLang = '{{ trans('public.can_not_try_again_quiz_toast_msg_lang') }}';
        var canNotDownloadCertificateToastTitleLang = '{{ trans('public.can_not_download_certificate_toast_lang') }}';
        var canNotDownloadCertificateToastMsgLang = '{{ trans('public.can_not_download_certificate_toast_msg_lang') }}';
        var sessionFinishedToastTitleLang = '{{ trans('public.session_finished_toast_title_lang') }}';
        var sessionFinishedToastMsgLang = '{{ trans('public.session_finished_toast_msg_lang') }}';
        var sequenceContentErrorModalTitle = '{{ trans('update.sequence_content_error_modal_title') }}';
        var courseHasBoughtStatusToastTitleLang = '{{ trans('cart.fail_purchase') }}';
        var courseHasBoughtStatusToastMsgLang = '{{ trans('site.you_bought_webinar') }}';
        var courseNotCapacityStatusToastTitleLang = '{{ trans('public.request_failed') }}';
        var courseNotCapacityStatusToastMsgLang = '{{ trans('cart.course_not_capacity') }}';
        var courseHasStartedStatusToastTitleLang = '{{ trans('cart.fail_purchase') }}';
        var courseHasStartedStatusToastMsgLang = '{{ trans('update.class_has_started') }}';
    </script>

    <script src="/assets/default/js/parts/comment.min.js"></script>
    <script src="/assets/default/js/parts/video_player_helpers.min.js"></script>
    <script src="/assets/default/js/parts/webinar_show.min.js"></script>
    @include('web.default.includes.booking_actions')
@endpush
