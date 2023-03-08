@php
    $getPanelSidebarSettings = getPanelSidebarSettings();
@endphp

<div class="xs-panel-nav d-none justify-content-between py-5 px-15">
    <div class="user-info d-flex align-items-center justify-content-between">
        <div class="user-avatar bg-gray200">
            <img src="{{ $authUser->getAvatar(100) }}" class="img-cover" alt="{{ $authUser->full_name }}">
        </div>

        <div class="user-name ml-15">
            <h3 class="font-16 font-weight-bold">{{ $authUser->full_name }}</h3>
        </div>
    </div>

    <button
        class="sidebar-toggler btn-transparent d-flex flex-column-reverse justify-content-center align-items-center p-5 rounded-sm sidebarNavToggle"
        type="button">
        <span>{{ trans('navbar.menu') }}</span>
        <i data-feather="menu" width="16" height="16"></i>
    </button>
</div>

<div class="panel-sidebar pt-10 pt-md-50 px-10 px-md-20" id="panelSidebar">
    <div class="user-info d-flex align-items-center flex-column justify-content-lg-center">
        <a href="/panel" class="dashboard-profile-img">
            <img src="{{ $authUser->getAvatar(100) }}" class="img-cover" alt="{{ $authUser->full_name }}">
        </a>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <a href="/panel" class="mt-10 mb-15">
                <h3 class="sidebar-username text-center font-12 font-weight-500 ">{{ $authUser->full_name }}</h3>
            </a>

            @if (!empty($authUser->getUserGroup()))
                <span class="create-new-user mt-10">{{ $authUser->getUserGroup()->name }}</span>
            @endif
        </div>
    </div>

   {{--  <div class="d-flex sidebar-user-stats pb-10 ml-20 pb-lg-20 mt-15 mt-lg-30">
        <div class="sidebar-user-stat-item d-flex flex-column">
            <strong class="text-center">{{ $authUser->webinars()->count() }}</strong>
            <span class="font-12">{{ trans('panel.classes') }}</span>
        </div>

        <div class="border-left mx-30"></div>

        @if ($authUser->isUser())
            <div class="sidebar-user-stat-item d-flex flex-column">
                <strong class="text-center">{{ $authUser->following()->count() }}</strong>
                <span class="font-12">{{ trans('panel.following') }}</span>
            </div>
        @else
            <div class="sidebar-user-stat-item d-flex flex-column">
                <strong class="text-center">{{ $authUser->followers()->count() }}</strong>
                <span class="font-12">{{ trans('panel.followers') }}</span>
            </div>
        @endif
    </div> --}}

    <ul id="panel-sidebar-scroll"
        class="sidebar-menu pt-10 @if (!empty($authUser->userGroup)) has-user-group @endif @if (empty($getPanelSidebarSettings) or empty($getPanelSidebarSettings['background'])) without-bottom-image @endif"
        @if (!empty($isRtl) and $isRtl) data-simplebar-direction="rtl" @endif>
        <li class="p-2 sidenav-item mt-2 mt-md-2 {{ request()->is('panel') ? 'sidenav-item-active' : '' }}">
            <a href="/panel" class="d-flex align-items-center">
                <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-8.svg" alt="">
                <span class="font-12 font-weight-500">{{ trans('panel.dashboard') }}</span>
            </a>
        </li>
        <li
            class="p-2 sidenav-item mt-2 mt-md-2 {{ (request()->is('panel/meetings') or request()->is('panel/meetings/*')) ? 'sidenav-item-active' : '' }}">
            <a href="/panel/meetings/reservation" class="d-flex align-items-center"{{--  data-toggle="collapse" href="#meetingCollapse" role="button" --}}
                {{-- aria-expanded="false" aria-controls="meetingCollapse" --}}>
                <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector.svg" alt="">
                <span class="font-12 font-weight-500">{{ trans('sidebar.classes') }}</span>
                @if ($authUser->isTeacher() && $authUser->upcomingClassesStudent()->count() > 0)
                    <span class="counter-badge ml-2">
                        {{ $authUser->upcomingClasses()->count() }}
                    </span>
                @elseif(!$authUser->isTeacher() && $authUser->upcomingClassesStudent()->count() > 0)
                    <span class="counter-badge ml-2">
                        {{ $authUser->upcomingClassesStudent()->count() }}
                    </span>
                @endif


            </a>

            {{--  <div class="collapse {{ (request()->is('panel/meetings') or request()->is('panel/meetings/*')) ? 'show' : '' }}"
                id="meetingCollapse">
                <ul class="sidenav-item-collapse">

                    <li class="mt-5 {{ request()->is('panel/meetings/reservation') ? 'active' : '' }}">
                        <a href="/panel/meetings/reservation">{{ trans('public.my_reservation') }}</a>
                    </li>

                    @if ($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ request()->is('panel/meetings/requests') ? 'active' : '' }}">
                            <a href="/panel/meetings/requests">{{ trans('panel.requests') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/meetings/settings') ? 'active' : '' }}">
                            <a href="/panel/meetings/settings">{{ trans('panel.settings') }}</a>
                        </li>
                    @endif
                </ul>
            </div> --}}
        </li>
        <li class="p-2 sidenav-item mt-2 mt-md-2 {{ request()->is('panel/chat*') ? 'sidenav-item-active' : '' }}">
            <a href="/panel/chat" class="d-flex align-items-center">
                <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-1.svg" alt="">
                <span class="font-12 font-weight-500">{{ trans('sidebar.chat') }}</span>
                @if ($authUser->recivedUnreadedMessages()->count() > 0)
                    <span class="counter-badge ml-2">
                        {{ $authUser->recivedUnreadedMessages()->count() }}
                    </span>
                @endif
            </a>
        </li>


        @if ($authUser->isOrganization())
            <li
                class="sidenav-item {{ (request()->is('panel/instructors') or request()->is('panel/manage/instructors*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#instructorsCollapse" role="button"
                    aria-expanded="false" aria-controls="instructorsCollapse">
                    <span class="sidenav-item-icon mr-10">
                        @include('web.default.panel.includes.sidebar_icons.teachers')
                    </span>
                    <span class="font-12 font-weight-500">{{ trans('public.instructors') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/instructors') or request()->is('panel/manage/instructors*')) ? 'show' : '' }}"
                    id="instructorsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ request()->is('panel/instructors/new') ? 'active' : '' }}">
                            <a href="/panel/manage/instructors/new">{{ trans('public.new') }}</a>
                        </li>
                        <li class="mt-5 {{ request()->is('panel/manage/instructors') ? 'active' : '' }}">
                            <a href="/panel/manage/instructors">{{ trans('public.list') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li
                class="sidenav-item {{ (request()->is('panel/students') or request()->is('panel/manage/students*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#studentsCollapse" role="button"
                    aria-expanded="false" aria-controls="studentsCollapse">
                    <span class="sidenav-item-icon mr-10">
                        @include('web.default.panel.includes.sidebar_icons.students')
                    </span>
                    <span class="font-12 font-weight-500">{{ trans('quiz.students') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/students') or request()->is('panel/manage/students*')) ? 'show' : '' }}"
                    id="studentsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ request()->is('panel/manage/students/new') ? 'active' : '' }}">
                            <a href="/panel/manage/students/new">{{ trans('public.new') }}</a>
                        </li>
                        <li class="mt-5 {{ request()->is('panel/manage/students') ? 'active' : '' }}">
                            <a href="/panel/manage/students">{{ trans('public.list') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif



        {{--         @if ($authUser->isOrganization() or $authUser->isTeacher())
            <li class="sidenav-item {{ (request()->is('panel/bundles') or request()->is('panel/bundles/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#bundlesCollapse" role="button" aria-expanded="false" aria-controls="bundlesCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('web.default.panel.includes.sidebar_icons.bundles')
                </span>
                    <span class="font-12 font-weight-500">{{ trans('update.bundles') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/bundles') or request()->is('panel/bundles/*')) ? 'show' : '' }}" id="bundlesCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ (request()->is('panel/bundles/new')) ? 'active' : '' }}">
                            <a href="/panel/bundles/new">{{ trans('public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ (request()->is('panel/bundles')) ? 'active' : '' }}">
                            <a href="/panel/bundles">{{ trans('update.my_bundles') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif --}}

        {{-- @if (getFeaturesSettings('webinar_assignment_status'))
            <li
                class="sidenav-item {{ (request()->is('panel/assignments') or request()->is('panel/assignments/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#assignmentCollapse"
                    role="button" aria-expanded="false" aria-controls="assignmentCollapse">
                    <span class="sidenav-item-icon mr-10">
                        @include('web.default.panel.includes.sidebar_icons.assignments')
                    </span>
                    <span class="font-12 font-weight-500">{{ trans('update.assignments') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/assignments') or request()->is('panel/assignments/*')) ? 'show' : '' }}"
                    id="assignmentCollapse">
                    <ul class="sidenav-item-collapse">

                        <li class="mt-5 {{ request()->is('panel/assignments/my-assignments') ? 'active' : '' }}">
                            <a href="/panel/assignments/my-assignments">{{ trans('update.my_assignments') }}</a>
                        </li>

                        @if ($authUser->isOrganization() || $authUser->isTeacher())
                            <li
                                class="mt-5 {{ request()->is('panel/assignments/my-courses-assignments') ? 'active' : '' }}">
                                <a
                                    href="/panel/assignments/my-courses-assignments">{{ trans('update.students_assignments') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif --}}
        @if (!$authUser->isTeacher())
            <li
                class="p-2 sidenav-item mt-2 mt-md-2 {{ request()->is('panel/my-teachers') ? 'sidenav-item-active' : '' }}">
                <a href="/panel/my-teachers" class="d-flex align-items-center">
                    <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-2.svg" alt="">
                    <span class="font-12 font-weight-500">{{ trans('sidebar.my_teachers') }}</span>
                </a>
            </li>
        @endif
        
        @if ($authUser->isTeacher())
            <li
                class="p-2 sidenav-item mt-2 mt-md-2 {{ request()->is('panel/my-students') ? 'sidenav-item-active' : '' }}">
                <a href="/panel/my-students" class="d-flex align-items-center">
                    <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-2.svg" alt="">
                    <span class="font-12 font-weight-500">{{ trans('sidebar.my_students') }}</span>
                </a>
            </li>
        @endif

        <li
            class="p-2 sidenav-item mt-2 mt-md-2 {{ request()->is('panel/homeworks*') ? 'sidenav-item-active' : '' }}">
            <a href="/panel/homeworks" class="d-flex align-items-center">
                <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-3.svg" alt="">
                <span class="font-12 font-weight-500">{{ trans('sidebar.homeworks') }}</span>
                <span class="counter-badge ml-2">
                    {{ $authUser->receivedPendingHomeworks()->count() > 0 ? $authUser->receivedPendingHomeworks()->count() : 0 }}
                </span>
            </a>
        </li>
        <li class="p-2 sidenav-item mt-2 mt-md-2 {{ request()->is('panel/quizes*') ? 'sidenav-item-active' : '' }}">
            <a href="/panel/quizes" class="d-flex align-items-center">
                <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-4.svg" alt="">
                <span class="font-12 font-weight-500">{{ trans('sidebar.quizes') }}</span>
                <span class="counter-badge ml-2">
                    {{ $authUser->receivedPendingQuizes()->count() > 0 ? $authUser->receivedPendingQuizes()->count() : '' }}
                </span>

            </a>
        </li>
        {{-- <li
            class="sidenav-item {{ (request()->is('panel/quizzes') or request()->is('panel/quizzes/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#quizzesCollapse" role="button"
                aria-expanded="false" aria-controls="quizzesCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.quizzes')
                </span>
                <span class="font-12 font-weight-500">{{ trans('sidebar.quizzes') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/quizzes') or request()->is('panel/quizzes/*')) ? 'show' : '' }}"
                id="quizzesCollapse">
                <ul class="sidenav-item-collapse">
                    @if ($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ request()->is('panel/quizzes/new') ? 'active' : '' }}">
                            <a href="/panel/quizzes/new">{{ trans('quiz.new_quiz') }}</a>
                        </li>
                        <li class="mt-5 {{ request()->is('panel/quizzes') ? 'active' : '' }}">
                            <a href="/panel/quizzes">{{ trans('public.list') }}</a>
                        </li>
                        <li class="mt-5 {{ request()->is('panel/quizzes/results') ? 'active' : '' }}">
                            <a href="/panel/quizzes/results">{{ trans('public.results') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ request()->is('panel/quizzes/my-results') ? 'active' : '' }}">
                        <a href="/panel/quizzes/my-results">{{ trans('public.my_results') }}</a>
                    </li>

                    <li class="mt-5 {{ request()->is('panel/quizzes/opens') ? 'active' : '' }}">
                        <a href="/panel/quizzes/opens">{{ trans('public.not_participated') }}</a>
                    </li>
                </ul>
            </div>
        </li> --}}

        {{-- <li class="sidenav-item {{ (request()->is('panel/certificates') or request()->is('panel/certificates/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#certificatesCollapse" role="button" aria-expanded="false" aria-controls="certificatesCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.certificate')
                </span>
                <span class="font-12 font-weight-500">{{ trans('panel.certificates') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/certificates') or request()->is('panel/certificates/*')) ? 'show' : '' }}" id="certificatesCollapse">
                <ul class="sidenav-item-collapse">
                    @if ($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ (request()->is('panel/certificates')) ? 'active' : '' }}">
                            <a href="/panel/certificates">{{ trans('public.list') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ (request()->is('panel/certificates/achievements')) ? 'active' : '' }}">
                        <a href="/panel/certificates/achievements">{{ trans('quiz.achievements') }}</a>
                    </li>

                    <li class="mt-5">
                        <a href="/certificate_validation">{{ trans('site.certificate_validation') }}</a>
                    </li>

                    <li class="mt-5 {{ (request()->is('panel/certificates/webinars')) ? 'active' : '' }}">
                        <a href="/panel/certificates/webinars">{{ trans('update.course_certificates') }}</a>
                    </li>

                </ul>
            </div>
        </li> --}}

        @if ($authUser->checkCanAccessToStore())
            <li
                class="sidenav-item {{ (request()->is('panel/store') or request()->is('panel/store/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#storeCollapse" role="button"
                    aria-expanded="false" aria-controls="storeCollapse">
                    <span class="sidenav-item-icon assign-fill mr-10">
                        @include('web.default.panel.includes.sidebar_icons.store')
                    </span>
                    <span class="font-12 font-weight-500">{{ trans('update.store') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/store') or request()->is('panel/store/*')) ? 'show' : '' }}"
                    id="storeCollapse">
                    <ul class="sidenav-item-collapse">
                        @if ($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ request()->is('panel/store/products/new') ? 'active' : '' }}">
                                <a href="/panel/store/products/new">{{ trans('update.new_product') }}</a>
                            </li>

                            <li class="mt-5 {{ request()->is('panel/store/products') ? 'active' : '' }}">
                                <a href="/panel/store/products">{{ trans('update.products') }}</a>
                            </li>

                            @php
                                $sellerProductOrderWaitingDeliveryCount = $authUser->getWaitingDeliveryProductOrdersCount();
                            @endphp

                            <li class="mt-5 {{ request()->is('panel/store/sales') ? 'active' : '' }}">
                                <a href="/panel/store/sales">{{ trans('panel.sales') }}</a>

                                @if ($sellerProductOrderWaitingDeliveryCount > 0)
                                    <span
                                        class="d-inline-flex align-items-center justify-content-center font-weight-500 ml-15 panel-sidebar-store-sales-circle-badge">{{ $sellerProductOrderWaitingDeliveryCount }}</span>
                                @endif
                            </li>

                        @endif

                        <li class="mt-5 {{ request()->is('panel/store/purchases') ? 'active' : '' }}">
                            <a href="/panel/store/purchases">{{ trans('panel.my_purchases') }}</a>
                        </li>

                        @if ($authUser->isOrganization() || $authUser->isTeacher())
                            <li class="mt-5 {{ request()->is('panel/store/products/comments') ? 'active' : '' }}">
                                <a href="/panel/store/products/comments">{{ trans('update.product_comments') }}</a>
                            </li>
                        @endif

                        <li class="mt-5 {{ request()->is('panel/store/products/my-comments') ? 'active' : '' }}">
                            <a href="/panel/store/products/my-comments">{{ trans('panel.my_comments') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        @if (!$authUser->isTeacher())
            {{-- <li
                class="sidenav-item {{ (request()->is('panel/financial') or request()->is('panel/financial/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#financialCollapse" role="button"
                    aria-expanded="false" aria-controls="financialCollapse">
                    <span class="sidenav-item-icon mr-10">
                        @include('web.default.panel.includes.sidebar_icons.financial')
                    </span>
                    <span class="font-12 font-weight-500">{{ trans('panel.financial') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/financial') or request()->is('panel/financial/*')) ? 'show' : '' }}"
                    id="financialCollapse">
                    <ul class="sidenav-item-collapse">





                        <li class="mt-5 {{ request()->is('panel/financial/payout') ? 'active' : '' }}">
                            <a href="/panel/financial/payout">{{ trans('financial.payout') }}</a>
                        </li>



                        @if ($authUser->isOrganization() || $authUser->isTeacher() and getRegistrationPackagesGeneralSettings('status'))
                            <li
                                class="mt-5 {{ request()->is('panel/financial/registration-packages') ? 'active' : '' }}">
                                <a
                                    href="{{ route('panelRegistrationPackagesLists') }}">{{ trans('update.registration_packages') }}</a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li> --}}
        @endif

        {{--         <li class="sidenav-item {{ (request()->is('panel/support') or request()->is('panel/support/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#supportCollapse" role="button" aria-expanded="false" aria-controls="supportCollapse">
                <span class="sidenav-item-icon assign-fill mr-10">
                    @include('web.default.panel.includes.sidebar_icons.support')
                </span>
                <span class="font-12 font-weight-500">{{ trans('panel.support') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/support') or request()->is('panel/support/*')) ? 'show' : '' }}" id="supportCollapse">
                <ul class="sidenav-item-collapse">
                    <li class="mt-5 {{ (request()->is('panel/support/new')) ? 'active' : '' }}">
                        <a href="/panel/support/new">{{ trans('public.new') }}</a>
                    </li>
                    <li class="mt-5 {{ (request()->is('panel/support')) ? 'active' : '' }}">
                        <a href="/panel/support">{{ trans('panel.classes_support') }}</a>
                    </li>
                    <li class="mt-5 {{ (request()->is('panel/support/tickets')) ? 'active' : '' }}">
                        <a href="/panel/support/tickets">{{ trans('panel.support_tickets') }}</a>
                    </li>
                </ul>
            </div>
        </li> --}}

        @if (!$authUser->isUser() or !empty($referralSettings) and $referralSettings['status'] and $authUser->affiliate)


            @if (!empty($referralSettings) and $referralSettings['status'] and $authUser->affiliate)
                <li
                    class="p-2 sidenav-item mt-2 mt-md-2 {{ request()->is('panel/marketing*') ? 'sidenav-item-active' : '' }}">
                    <a href="/panel/marketing/affiliates" class="d-flex align-items-center">
                        <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-5.svg" alt="">
                        <span
                            class="font-12 font-weight-500">{{ trans('panel.affiliates') }}</span>
                    </a>
                </li>
            @endif

        @endif

        @if (getFeaturesSettings('forums_status'))
            <li
                class="sidenav-item {{ (request()->is('panel/forums') or request()->is('panel/forums/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#forumsCollapse" role="button"
                    aria-expanded="false" aria-controls="forumsCollapse">
                    <span class="sidenav-item-icon assign-fill mr-10">
                        @include('web.default.panel.includes.sidebar_icons.forums')
                    </span>
                    <span class="font-12 font-weight-500">{{ trans('update.forums') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/forums') or request()->is('panel/forums/*')) ? 'show' : '' }}"
                    id="forumsCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ request()->is('/forums/create-topic') ? 'active' : '' }}">
                            <a href="/forums/create-topic">{{ trans('update.new_topic') }}</a>
                        </li>
                        <li class="mt-5 {{ request()->is('panel/forums/topics') ? 'active' : '' }}">
                            <a href="/panel/forums/topics">{{ trans('update.my_topics') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/forums/posts') ? 'active' : '' }}">
                            <a href="/panel/forums/posts">{{ trans('update.my_posts') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/forums/bookmarks') ? 'active' : '' }}">
                            <a href="/panel/forums/bookmarks">{{ trans('update.bookmarks') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif


        {{-- @if ($authUser->isTeacher())
            <li
                class="sidenav-item {{ (request()->is('panel/blog') or request()->is('panel/blog/*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#blogCollapse" role="button"
                    aria-expanded="false" aria-controls="blogCollapse">
                    <span class="sidenav-item-icon assign-fill mr-10">
                        @include('web.default.panel.includes.sidebar_icons.blog')
                    </span>
                    <span class="font-12 font-weight-500">{{ trans('update.articles') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/blog') or request()->is('panel/blog/*')) ? 'show' : '' }}"
                    id="blogCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ request()->is('panel/blog/posts/new') ? 'active' : '' }}">
                            <a href="/panel/blog/posts/new">{{ trans('update.new_article') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/blog/posts') ? 'active' : '' }}">
                            <a href="/panel/blog/posts">{{ trans('update.my_articles') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/blog/comments') ? 'active' : '' }}">
                            <a href="/panel/blog/comments">{{ trans('panel.comments') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif --}}

        {{-- @if ($authUser->isOrganization() || $authUser->isTeacher())
            <li
                class="sidenav-item {{ (request()->is('panel/noticeboard*') or request()->is('panel/course-noticeboard*')) ? 'sidenav-item-active' : '' }}">
                <a class="d-flex align-items-center" data-toggle="collapse" href="#noticeboardCollapse"
                    role="button" aria-expanded="false" aria-controls="noticeboardCollapse">
                    <span class="sidenav-item-icon mr-10">
                        @include('web.default.panel.includes.sidebar_icons.noticeboard')
                    </span>

                    <span class="font-12 font-weight-500">{{ trans('panel.noticeboard') }}</span>
                </a>

                <div class="collapse {{ (request()->is('panel/noticeboard*') or request()->is('panel/course-noticeboard*')) ? 'show' : '' }}"
                    id="noticeboardCollapse">
                    <ul class="sidenav-item-collapse">
                        <li class="mt-5 {{ request()->is('panel/noticeboard') ? 'active' : '' }}">
                            <a href="/panel/noticeboard">{{ trans('public.history') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/noticeboard/new') ? 'active' : '' }}">
                            <a href="/panel/noticeboard/new">{{ trans('public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/course-noticeboard') ? 'active' : '' }}">
                            <a href="/panel/course-noticeboard">{{ trans('update.course_notices') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/course-noticeboard/new') ? 'active' : '' }}">
                            <a href="/panel/course-noticeboard/new">{{ trans('update.new_course_notices') }}</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif --}}

        {{--         @php
            $rewardSetting = getRewardsSettings();
        @endphp

        @if (!empty($rewardSetting) and $rewardSetting['status'] == '1')
            <li class="sidenav-item {{ request()->is('panel/rewards') ? 'sidenav-item-active' : '' }}">
                <a href="/panel/rewards" class="d-flex align-items-center">
                    <span class="sidenav-item-icon assign-strock mr-10">
                        @include('web.default.panel.includes.sidebar_icons.rewards')
                    </span>
                    <span class="font-12 font-weight-500">{{ trans('update.rewards') }}</span>
                </a>
            </li>
        @endif
 --}}
        {{-- <li class="sidenav-item {{ (request()->is('panel/notifications')) ? 'sidenav-item-active' : '' }}">
            <a href="/panel/notifications" class="d-flex align-items-center">
            <span class="sidenav-notification-icon sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.notifications')
                </span>
                <span class="font-12 font-weight-500">{{ trans('panel.notifications') }}</span>
            </a>
        </li> --}}

        {{-- <li
            class="sidenav-item {{ (request()->is('panel/webinars') or request()->is('panel/webinars/*')) ? 'sidenav-item-active' : '' }}">
            <a class="d-flex align-items-center" data-toggle="collapse" href="#webinarCollapse" role="button"
                aria-expanded="false" aria-controls="webinarCollapse">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.webinars')
                </span>
                <span class="font-12 font-weight-500">{{ trans('panel.webinars') }}</span>
            </a>

            <div class="collapse {{ (request()->is('panel/webinars') or request()->is('panel/webinars/*')) ? 'show' : '' }}"
                id="webinarCollapse">
                <ul class="sidenav-item-collapse">
                    @if ($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ request()->is('panel/webinars/new') ? 'active' : '' }}">
                            <a href="/panel/webinars/new">{{ trans('public.new') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/webinars') ? 'active' : '' }}">
                            <a href="/panel/webinars">{{ trans('panel.my_classes') }}</a>
                        </li>

                        <li class="mt-5 {{ request()->is('panel/webinars/invitations') ? 'active' : '' }}">
                            <a href="/panel/webinars/invitations">{{ trans('panel.invited_classes') }}</a>
                        </li>
                    @endif

                    @if (!empty($authUser->organ_id))
                        <li class="mt-5 {{ request()->is('panel/webinars/organization_classes') ? 'active' : '' }}">
                            <a
                                href="/panel/webinars/organization_classes">{{ trans('panel.organization_classes') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ request()->is('panel/webinars/purchases') ? 'active' : '' }}">
                        <a href="/panel/webinars/purchases">{{ trans('panel.my_purchases') }}</a>
                    </li>

                    @if ($authUser->isOrganization() || $authUser->isTeacher())
                        <li class="mt-5 {{ request()->is('panel/webinars/comments') ? 'active' : '' }}">
                            <a href="/panel/webinars/comments">{{ trans('panel.my_class_comments') }}</a>
                        </li>
                    @endif

                    <li class="mt-5 {{ request()->is('panel/webinars/my-comments') ? 'active' : '' }}">
                        <a href="/panel/webinars/my-comments">{{ trans('panel.my_comments') }}</a>
                    </li>

                    <li class="mt-5 {{ request()->is('panel/webinars/favorites') ? 'active' : '' }}">
                        <a href="/panel/webinars/favorites">{{ trans('panel.favorites') }}</a>
                    </li>
                </ul>
            </div>
        </li> --}}

        <li class="p-2 sidenav-item mt-2 mt-md-2 {{ request()->is('panel/setting') ? 'sidenav-item-active' : '' }}">
            <a href="/panel/setting" class="d-flex align-items-center">
                <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-6.svg" alt="">
                <span class="font-12 font-weight-500">{{ trans('sidebar.settings') }}</span>
            </a>
        </li>



        {{-- @if ($authUser->isTeacher() or $authUser->isOrganization())
            <li class="sidenav-item ">
                <a href="{{ $authUser->getProfileUrl() }}" class="d-flex align-items-center">
                <span class="sidenav-item-icon assign-strock mr-10">
                    <i data-feather="user" stroke="#1f3b64" stroke-width="1.5" width="24" height="24" class="mr-10 webinar-icon"></i>
                </span>
                    <span class="font-12 font-weight-500">{{ trans('public.my_profile') }}</span>
                </a>
            </li>
        @endif --}}
        {{--         <li class="sidenav-item {{ request()->is('panel/notifications') ? 'sidenav-item-active' : '' }}">
            <a href="/panel/notifications" class="d-flex align-items-center">
                <span class="sidenav-item-icon mr-10">
                    @include('web.default.panel.includes.sidebar_icons.noticeboard')
                </span>
                <span class="font-12 font-weight-500">{{ trans('sidebar.notifications') }}</span>
            </a>
        </li> --}}

        <li class="p-2 sidenav-item mt-2">
            <a href="/logout" class="d-flex align-items-center">
                <img class="mr-md-10" src="/assets/default/img/dashboard-icons/Vector-7.svg" alt="">
                <span class="font-12 font-weight-500">{{ trans('sidebar.log_out') }}</span>
            </a>
        </li>
    </ul>

    {{--    @if (!empty($getPanelSidebarSettings) and !empty($getPanelSidebarSettings['background']))
        <div class="sidebar-create-class d-none d-md-block">
            <a href="{{ url('instructor-finder') }}" class="sidebar-create-class-btn d-block text-right p-5">
                <img src="{{ !empty($getPanelSidebarSettings['background']) ? $getPanelSidebarSettings['background'] : '' }}"
                    alt="">
            </a>
        </div>
    @endif --}}
</div>
