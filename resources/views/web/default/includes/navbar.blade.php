@php
    if (empty($authUser) and auth()->check()) {
        $authUser = auth()->user();
    }

    $navBtnUrl = null;
    $navBtnText = null;

    if (request()->is('forums*')) {
        $navBtnUrl = '/forums/create-topic';
        $navBtnText = trans('update.create_new_topic');
    } else {
        $navbarButton = getNavbarButton(!empty($authUser) ? $authUser->role_id : null);

        if (!empty($navbarButton)) {
            $navBtnUrl = $navbarButton->url;
            $navBtnText = $navbarButton->title;
        }
    }
@endphp

@php
    $userLanguages = !empty($generalSettings['site_language']) ? [$generalSettings['site_language'] => getLanguages($generalSettings['site_language'])] : [];

    if (!empty($generalSettings['user_languages']) and is_array($generalSettings['user_languages'])) {
        $userLanguages = getLanguages($generalSettings['user_languages']);
    }

    $localLanguage = [];

    foreach ($userLanguages as $key => $userLanguage) {
        $localLanguage[localeToCountryCode($key)] = $userLanguage;
    }

@endphp

<div id="navbarVacuum"></div>
<nav id="navbar" class="navbar navbar-expand-lg navbar-light tulkka-navbar">
    <div class="{{ (!empty($isPanel) and $isPanel) ? 'container-fluid' : 'container-fluid' }}">
        <div class="d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center">
                <a class="navbar-brand d-flex align-items-center justify-content-center mr-0 {{ (empty($navBtnUrl) and empty($navBtnText)) ? 'ml-auto' : '' }}"
                    href="/">
                    @if (!empty($generalSettings['logo']))
                        <img src="{{ url('/store/1/comp-logo-footer.png') }}" class="comp-logo d-none d-lg-block" alt="site logo">
                        <img src="{{ url('/store/1/comp-logo-white.png') }}" class="comp-logo d-block d-lg-none ml-60"
                            alt="site logo">
                    @endif
                </a>

                <form action="/search" method="get"
                    class="form-inline my-2 my-lg-0 navbar-search position-relative mx-2 mx-xl-30 d-none d-lg-block">
                    <div class="d-flex">
                        <input class="form-control border-primary d-none d-xl-inline-block" type="text"
                            name="search" placeholder="{{ trans('navbar.search_anything') }}" aria-label="Search">
                        <button class="p-1 p-xl-2 bg-primary d-flex text-center align-items-center br-none" type="submit">
                            <i data-feather="search"
                                width="20" height="20" class="text-white"></i></button>
                    </div>
                </form>
            </div>

            <div class="d-flex flex-row-reverse flex-md-row flex-lg-grow-1">
                <button class="navbar-toggler navbar-order" type="button" id="navbarToggle">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="d-none d-lg-flex flex-grow-1 navbar-toggle-content" id="navbarContent">
                    <div
                        class="navbar-toggle-header text-right d-lg-none d-flex align-items-center justify-content-between">
                        <img src="{{ url('store/1/comp-logo-white.png') }}" class="comp-logo-mobile d-inline d-lg-none"
                            alt="site logo">
                        <button class="btn-transparent" id="navbarClose">
                            <i class="fa-regular fa-x close-icon text-white"></i>
                        </button>
                    </div>

                    <form action="/search" method="get"
                        class="form-inline my-30 my-lg-0 navbar-search position-relative d-block d-lg-none ">
                        <div class="d-flex"><input class="form-control border-primary w-100" type="text" name="search"
                                placeholder="{{ trans('navbar.search_anything') }}" aria-label="Search"><span
                                class=" p-2 bg-primary d-flex text-center align-items-center"><i data-feather="search"
                                    width="20" height="20" class="text-white"></i></span></div>
                    </form>

                    <ul class="navbar-nav mx-auto d-flex align-items-center">
                        @if (!empty($navbarPages) and count($navbarPages))
                            @foreach ($navbarPages as $navbarPage)
                                @if ($navbarPage['title'] == 'Courses')
                                    @continue
                                @endif
                                @if (false && $navbarPage['title'] == 'Teachers')
                                    @if (!empty($categories) and count($categories))
                                        <li class="nav-item rounded mt-2 mt-md-0 text-nowrap">
                                            <div class="menu-category">
                                                <ul>
                                                    <li
                                                        class="cursor-pointer user-select-none d-flex xs-categories-toggle text-white">
                                                        {{ trans('navbar.teachers') }}
                                                        <ul class="cat-dropdown-menu">
                                                            @foreach ($categories as $category)
                                                                <li>
                                                                    <a
                                                                        href="{{ url("instructor-finder?level_of_training=&level_of_training=&gender=&category_id=$category->id&min_time=&max_time=") }} {{-- {{ (!empty($category->subCategories) and count($category->subCategories)) ? '#!' : $category->getUrl() }} --}}">
                                                                        <div class="d-flex align-items-center">
                                                                            {{-- <img src="{{ $category->icon }}"
                                                        class="cat-dropdown-menu-icon mr-10"
                                                        alt="{{ $category->title }} icon"> --}}
                                                                            @if ($category->title == 'English')
                                                                                <span
                                                                                    class="cat-dropdown-menu-icon fi fi-us mr-10"></span>
                                                                            @elseif($category->title == 'French')
                                                                                <span
                                                                                    class="cat-dropdown-menu-icon fi fi-fr mr-10"></span>
                                                                            @elseif($category->title == 'Chinese Mandarin')
                                                                                <span
                                                                                    class="cat-dropdown-menu-icon fi fi-cn mr-10"></span>
                                                                            @elseif($category->title == 'Arabic')
                                                                                <span
                                                                                    class="cat-dropdown-menu-icon fi fi-sa mr-10"></span>
                                                                            @elseif($category->title == 'Spanish')
                                                                                <span
                                                                                    class="cat-dropdown-menu-icon fi fi-es mr-10"></span>
                                                                            @endif

                                                                            {{ trans('site.' . $category->title) }}
                                                                        </div>

                                                                        @if (!empty($category->subCategories) and count($category->subCategories))
                                                                            <i data-feather="chevron-right"
                                                                                width="20" height="20"
                                                                                class="d-none d-lg-inline-block ml-10"></i>
                                                                            <i data-feather="chevron-down"
                                                                                width="20" height="20"
                                                                                class="d-inline-block d-lg-none"></i>
                                                                        @endif
                                                                    </a>
                                                                    @if (!empty($category->subCategories) and count($category->subCategories))
                                                                        <ul class="sub-menu" data-simplebar
                                                                            @if (!empty($isRtl) and $isRtl) data-simplebar-direction="rtl" @endif>
                                                                            @foreach ($category->subCategories as $subCategory)
                                                                                <li>
                                                                                    <a
                                                                                        href="{{ $subCategory->getUrl() }}">
                                                                                        @if (!empty($subCategory->icon))
                                                                                            <img src="{{ $subCategory->icon }}"
                                                                                                class="cat-dropdown-menu-icon mr-10"
                                                                                                alt="{{ $subCategory->title }} icon">
                                                                                        @endif

                                                                                        {{ $subCategory->title }}
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                    @endif
                                    @continue
                                @endif
                                <li class="nav-item rounded mt-2 mt-md-0 text-nowrap">


                                    @if ($navbarPage['title'] == 'צור איתנו קשר')
                                        <a class="nav-link rounded text-white"
                                            href="{{ $navbarPage['link'] }}">{{ 'צור קשר' }}</a>
                                    @else
                                        <a class="nav-link rounded text-white"
                                            href="{{ $navbarPage['link'] }}">{{ $navbarPage['title'] }}</a>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                        @if ((isset($authUser) && !$authUser->isTeacher()) || !isset($authUser))
                            <li class="nav-item rounded mt-2 mt-md-0 text-nowrap">
                                <a @if (isset($authUser) && !$authUser->isTeacher() || !isset($authUser)) onclick='Livewire.emit("showModal","true")' @endif
                                    class="nav-link text-white">{{ trans('navbar.plans') }}</a>
                            </li>
                        @endif
                    </ul>

                    <div class="d-block d-md-none mb-15 mb-md-none">
                        @if (!empty($localLanguage) and count($localLanguage) > 1)
                            <form action="/locale" method="post" class="mr-15 mx-md-20">
                                {{ csrf_field() }}
                                <input type="hidden" name="locale">
                                <div class="language-select">
                                    <div id="localItems"
                                        data-selected-country="{{ localeToCountryCode(mb_strtoupper(app()->getLocale())) }}"
                                        data-countries='{{ json_encode($localLanguage) }}'></div>
                                </div>
                            </form>
                        @else
                            <div class="mr-15 mx-md-20"></div>
                        @endif
                    </div>
                    @if (!empty($authUser))
                    <div class="dropdown mt-20">
                        <a href="#!" class="navbar-user dropdown-toggle d-lg-none"
                            type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <span
                                class="user-name font-14">{{ $authUser->full_name }}</span>
                        </a>

                        <div class="dropdown-menu user-profile-dropdown" aria-labelledby="dropdownMenuButton">
                            <div class="d-md-none border-bottom mb-20 pb-10 text-right">
                                <i class="close-dropdown" data-feather="x" width="32" height="32"
                                    class="mr-10"></i>
                            </div>

                            <a class="dropdown-item" href="{{ $authUser->isAdmin() ? '/admin' : '/panel' }}">
                                <img src="/assets/default/img/icons/sidebar/dashboard.svg" width="25"
                                    alt="nav-icon">
                                <span class="font-14 text-dark-blue">{{ trans('public.my_panel') }}</span>
                            </a>
                            @if ($authUser->isTeacher() or $authUser->isOrganization())
                                <a class="dropdown-item" href="{{ $authUser->getProfileUrl() }}">
                                    <img src="/assets/default/img/icons/profile.svg" width="25" alt="nav-icon">
                                    <span class="font-14 text-dark-blue">{{ trans('public.my_profile') }}</span>
                                </a>
                            @endif
                            <a class="dropdown-item" href="/logout">
                                <img src="/assets/default/img/icons/sidebar/logout.svg" width="25"
                                    alt="nav-icon">
                                <span class="font-14 text-dark-blue">{{ trans('panel.log_out') }}</span>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="align-items-center ml-md-50 d-flex d-lg-none">
                        <a href="/login"
                            class="py-5 px-20 mx-10 text-white text-md-dark-blue font-14 border-white border-md-primary border ">{{ trans('auth.login') }}</a>
                        <a href="/register"
                            class="py-5 px-20 text-dark-blue font-14 bg-primary border text-white">{{ trans('auth.register') }}</a>
                    </div>
                @endif
                    @if (isset($authUser) && !$authUser->isPaidUser() && !$authUser->isTeacher())
                        <a onclick='Livewire.emit("showModal","SomeData")'
                            class="d-flex d-lg-none btn btn-sm btn-white nav-start-a-live-btn kreep text-primary mt-20">
                            {{ trans('navbar.subscribe_now') }}
                        </a>
                    @endif
                    @if (isset($authUser) && !$authUser->isTeacher() && !$authUser->trial_expired)
                        <a href="{{ url('/instructor-finder') }}"
                            @if (!isset($authUser)) href="{{ url('/login') }}" @endif
                            class="d-flex d-lg-none btn btn-sm btn-white nav-start-a-live-btn mt-2 text-primary">
                            {{ trans('navbar.trial') }}
                        </a>
                    @endif

                </div>

                <div class="nav-icons-or-start-live navbar-order d-flex align-items-center">
                    @if (isset($authUser) && !$authUser->isPaidUser() && !$authUser->isTeacher())
                        <a onclick='Livewire.emit("showModal","SomeData")'
                            class="d-none d-lg-flex btn btn-sm btn-primary nav-start-a-live-btn mr-2 ml-2  kreep">
                            {{ trans('navbar.subscribe_now') }}
                        </a>
                    @endif

                    @if (isset($authUser) && !$authUser->isTeacher() && $authUser->trial_expired)
                        <a href="{{ url('/instructor-finder') }}"
                            @if (!isset($authUser)) href="{{ url('/login') }}" @endif
                            class="d-none d-lg-flex btn btn-sm btn-primary nav-start-a-live-btn text-nowrap">
                            {{ trans('navbar.trial') }}
                        </a>
                    @endif

                    @if (isset($authUser) && !$authUser->trial_expired)
                        <a @if (!$authUser->isPaidUser() && !$authUser->isTeacher()) onclick='Livewire.emit("showModal","SomeData")'
                      @else
                      href="{{ url('/instructor-finder') }}" @endif
                            class="d-none d-lg-flex btn btn-sm btn-primary nav-start-a-live-btn">
                            {{ trans('navbar.book_lesson') }}
                        </a>
                    @endif
                    @if (!empty($authUser))
                        <a href="/panel" class="dashboard-profile-img ml-2 d-none d-xl-block">
                            <img src="{{ $authUser->getAvatar(100) }}" class="img-cover" alt="{{ $authUser->full_name }}">
                        </a>
                        <div class="dropdown">
                            <a href="#!"
                                class="navbar-user align-items-center dropdown-toggle d-none d-lg-flex"
                                type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">

                                <span
                                    class="font-16 user-name mx-10 text-dark-blue font-14">{{ $authUser->full_name }}</span>
                            </a>

                            <div class="dropdown-menu user-profile-dropdown" aria-labelledby="dropdownMenuButton">
                                <div class="d-md-none border-bottom mb-20 pb-10 text-right">
                                    <i class="close-dropdown" data-feather="x" width="32" height="32"
                                        class="mr-10"></i>
                                </div>

                                <a class="dropdown-item" href="{{ $authUser->isAdmin() ? '/admin' : '/panel' }}">
                                    <img src="/assets/default/img/icons/sidebar/dashboard.svg" width="25"
                                        alt="nav-icon">
                                    <span class="font-14 text-dark-blue">{{ trans('public.my_panel') }}</span>
                                </a>
                                @if ($authUser->isTeacher() or $authUser->isOrganization())
                                    <a class="dropdown-item" href="{{ $authUser->getProfileUrl() }}">
                                        <img src="/assets/default/img/icons/profile.svg" width="25"
                                            alt="nav-icon">
                                        <span class="font-14 text-dark-blue">{{ trans('public.my_profile') }}</span>
                                    </a>
                                @endif
                                <a class="dropdown-item" href="/logout">
                                    <img src="/assets/default/img/icons/sidebar/logout.svg" width="25"
                                        alt="nav-icon">
                                    <span class="font-14 text-dark-blue">{{ trans('panel.log_out') }}</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="align-items-center ml-md-50 d-none d-lg-flex">
                            <a href="/login"
                                class="py-5 px-20 mx-5 text-dark-blue font-14 border-primary border ">{{ trans('auth.login') }}</a>
                            <a href="/register"
                                class="py-5 px-20 text-dark-blue font-14 bg-primary border text-white">{{ trans('auth.register') }}</a>
                        </div>
                    @endif

                    <div
                        class="d-flex align-items-center justify-content-between justify-content-md-center d-none d-md-flex">
                        @if (!empty($localLanguage) and count($localLanguage) > 1)
                            <form action="/locale" method="post"
                                class=" border-left mr-15 mx-md-20 d-none d-lg-flex">
                                {{ csrf_field() }}
                                <input type="hidden" name="locale">
                                <div class="language-select">
                                    <div id="localItemsMobile"
                                        data-selected-country="{{ localeToCountryCode(mb_strtoupper(app()->getLocale())) }}"
                                        data-countries='{{ json_encode($localLanguage) }}'></div>
                                </div>
                            </form>
                        @else
                            <div class="mr-15 mx-md-20"></div>
                        @endif
                    </div>


                    @if (!empty($navBtnUrl))
                        {{--  <a href="{{ $navBtnUrl }}"
                            class="d-flex d-lg-none text-primary nav-start-a-live-btn font-14">
                            {{ trans('navbar.' . $navBtnText) }}
                        </a> --}}
                    @endif

                    <!-- <div class="d-none nav-notify-cart-dropdown top-navbar ">
                        @include(getTemplate() . '.includes.shopping-cart-dropdwon')

                        <div class="border-left mx-15"></div>

                        @include(getTemplate() . '.includes.notification-dropdown')
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</nav>

@push('scripts_bottom')
    <script src="/assets/default/js/parts/navbar.min.js"></script>
    <link href="/assets/default/vendors/flagstrap/css/flags.css" rel="stylesheet">
    <script src="/assets/default/vendors/flagstrap/js/jquery.flagstrap.min.js"></script>
    <script src="/assets/default/js/parts/top_nav_flags.min.js"></script>
@endpush
