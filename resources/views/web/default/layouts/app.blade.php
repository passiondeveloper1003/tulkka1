<!DOCTYPE html>
<html class="" id="appl" lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];

    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
@endphp


<head>
    @include('web.default.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <!-- <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-5.0.2/js/bootstrap.bundle.min.js"/> -->
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/default/css/app.css">
    <link rel="stylesheet" href="/assets/default/css/custom.css">
    <link rel="stylesheet" href="/assets/default/css/calendar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">


    @if($isRtl)
        <link rel="stylesheet" href="/assets/default/css/rtl-app.css">
        <link rel="stylesheet" href="/assets/default/css/rtl-custom.css">
    @endif

    @stack('styles_top')
    @stack('scripts_top')

    <style>
        {!! !empty(getCustomCssAndJs('css')) ? getCustomCssAndJs('css') : '' !!}

        {!! getThemeFontsSettings() !!}

        {!! getThemeColorsSettings() !!}
    </style>


    @if(!empty($generalSettings['preloading']) and $generalSettings['preloading'] == '1')
        @include('admin.includes.preloading')
    @endif

    @livewireStyles
    <script src="https://kit.fontawesome.com/463e1a2514.js" crossorigin="anonymous"></script>
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
  <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
  <link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css"
/>
</head>

<body class="@if($isRtl) rtl @endif">

<div id="app">

    @if(!isset($appHeader))
        @include('web.default.includes.navbar')
    @endif

    @if(!empty($justMobileApp))
        @include('web.default.includes.mobile_app_top_nav')
    @endif

    @include('loading')

    @yield('content')

    @if(!isset($appFooter))
        @include('web.default.includes.footer')
    @endif

    @include('web.default.includes.advertise_modal.index')
</div>
<!-- Template JS File -->
<script src="/assets/default/js/app.js"></script>
<script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
<script src="/assets/default/vendors/moment.min.js"></script>
<script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://www.jsviews.com/download/jsviews.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>




@if(empty($justMobileApp) and checkShowCookieSecurityDialog())
    @include('web.default.includes.cookie-security')
@endif


<script>
    var deleteAlertTitle = '{{ trans('public.are_you_sure') }}';
    var deleteAlertHint = '{{ trans('public.deleteAlertHint') }}';
    var deleteAlertConfirm = '{{ trans('public.deleteAlertConfirm') }}';
    var deleteAlertCancel = '{{ trans('public.cancel') }}';
    var deleteAlertSuccess = '{{ trans('public.success') }}';
    var deleteAlertFail = '{{ trans('public.fail') }}';
    var deleteAlertFailHint = '{{ trans('public.deleteAlertFailHint') }}';
    var deleteAlertSuccessHint = '{{ trans('public.deleteAlertSuccessHint') }}';
    var forbiddenRequestToastTitleLang = '{{ trans('public.forbidden_request_toast_lang') }}';
    var forbiddenRequestToastMsgLang = '{{ trans('public.forbidden_request_toast_msg_lang') }}';
    window.APP_URL = `{{ config('app.url') }}`;
    window.csrf = `{{ csrf_field() }}`;


</script>

@if(session()->has('toast'))
    <script>
        (function () {
            "use strict";

            $.toast({
                heading: '{{ session()->get('toast')['title'] ?? '' }}',
                text: '{{ session()->get('toast')['msg'] ?? '' }}',
                bgColor: '@if(session()->get('toast')['status'] == 'success') #43d477 @else #f63c3c @endif',
                textColor: 'white',
                hideAfter: 10000,
                position: 'bottom-right',
                icon: '{{ session()->get('toast')['status'] }}'
            });
        })(jQuery)
    </script>
@endif

@stack('styles_bottom')
@stack('scripts_bottom')

<script src="/assets/default/js/parts/main.min.js"></script>

<script>
    @if(session()->has('registration_package_limited'))
    (function () {
        "use strict";

        handleLimitedAccountModal('{!! session()->get('registration_package_limited') !!}')
    })(jQuery)

    {{ session()->forget('registration_package_limited') }}
    @endif


</script>
</a>


@livewire('subscription-modal')
@livewire('whats-goal-modal')
@livewire('video-modal')

@if(!empty($authUser) && $authUser->lastLessonHasNoFeedback() )
@livewire('after-lesson-feed-back-modal')
@endif

@livewireScripts

<script src="https://www.paypal.com/sdk/js?client-id={{ App::environment('production') ? config('paypal.live.client_id') : config('paypal.sandbox.client_id') }}&currency=ILS"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script>
let loaderElement = document.getElementById('laravel-livewire-loader');
let appl = document.getElementById('appl');
let loaderTimeout = null;

Livewire.hook('message.sent', () => {
    if (loaderTimeout == null) {
        loaderTimeout = setTimeout(() => {
            loaderElement.classList.remove('d-none');
            loaderElement.classList.add('d-flex');
            appl.classList.add('overflow-hidden');
        }, 2000);
    }
});

Livewire.hook('message.received', () => {
    if (loaderTimeout != null) {
        loaderElement.classList.remove('d-flex');
        loaderElement.classList.add('d-none');
        appl.classList.remove('overflow-hidden');
        clearTimeout(loaderTimeout);
        loaderTimeout = null;
    }
});
</script>


@if(App::environment('production'))
<script type="text/javascript">
  window.__lc = window.__lc || {};
  window.__lc.license = 14983482;
  ;(function(n,t,c){function i(n){return e._h?e._h.apply(null,n):e._q.push(n)};
  var e={_q:[],_h:null,_v:"2.0",on:function(){i(["on",c.call(arguments)])},once:function(){
  i(["once",c.call(arguments)])},off:function(){i(["off",c.call(arguments)])},
  get:function(){if(!e._h)throw new Error("[LiveChatWidget] You can't use getters before load.");
  return i(["get",c.call(arguments)])},call:function(){i(["call",c.call(arguments)])},init:function(){
  var n=t.createElement("script");
  n.async=!0,n.type="text/javascript",
  n.src="https://cdn.livechatinc.com/tracking.js",t.head.appendChild(n)}};
  !n.__lc.asyncInit&&e.init(),n.LiveChatWidget=n.LiveChatWidget||e}(window,document,[].slice))
</script>
<noscript>
<a href="https://www.livechatinc.com/chat-with/14983482/" rel="nofollow">Chat with us</a>,
powered by <a href="https://www.livechatinc.com/?welcome" rel="noopener nofollow" target="_blank">LiveChat</a>
</noscript>
<!-- End of LiveChat code -->
@endif
</body>
</html>
