<!DOCTYPE html>
<html id="appl" lang="{{ app()->getLocale() }}">

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];

    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
@endphp
<head>
    @include(getTemplate().'.includes.metas')
    <title>{{ $pageTitle ?? '' }}{{ !empty($generalSettings['site_name']) ? (' | '.$generalSettings['site_name']) : '' }}</title>

    <!-- General CSS File -->
    <link href="/assets/default/css/font.css" rel="stylesheet">

    <link rel="stylesheet" href="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/simplebar/simplebar.css">
    <link rel="stylesheet" href="/assets/default/css/app.css">
    <link rel="stylesheet" href="/assets/default/css/panel.css">
    <link rel="stylesheet" href="/assets/default/css/panel-custom.css">
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
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@6.6.6/css/flag-icons.min.css"
  />
</head>
<body class="@if($isRtl) rtl @endif">

@php
    $isPanel = true;
@endphp
@include('loading')
<div id="panel_app">
  {{-- @include('web.default.includes.top_nav') --}}
    @include(getTemplate().'.includes.navbar')


    <div class="d-flex justify-content-end panel-body">
        @include(getTemplate(). '.panel.includes.sidebar')

        <div class="panel-content">
            @yield('content')
        </div>
    </div>

    @include('web.default.includes.advertise_modal.index')
</div>
<!-- Template JS File -->
<script src="/assets/default/js/app.js"></script>
<script src="/assets/default/vendors/moment.min.js"></script>
<script src="/assets/default/vendors/feather-icons/dist/feather.min.js"></script>
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="/assets/default/vendors/sweetalert2/dist/sweetalert2.min.js"></script>
<script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>
<script type="text/javascript" src="/assets/default/vendors/simplebar/simplebar.min.js"></script>

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
<script src="/assets/default/js//parts/main.min.js"></script>

<script src="/assets/default/js/panel/public.min.js"></script>

<script>

    @if(session()->has('registration_package_limited'))
    (function () {
        "use strict";

        handleLimitedAccountModal('{!! session()->get('registration_package_limited') !!}')
    })(jQuery)

    {{ session()->forget('registration_package_limited') }}
    @endif

    {!! !empty(getCustomCssAndJs('js')) ? getCustomCssAndJs('js') : '' !!}
</script>
@livewire('subscription-modal')
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
</body>
</html>
