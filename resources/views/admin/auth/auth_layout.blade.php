<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ $pageTitle ?? '' }}</title>

    <!-- General CSS File -->
    <link rel="stylesheet" href="/assets/admin/vendor/bootstrap/bootstrap.min.css"/>
    <!-- <link rel="stylesheet" href="/assets/admin/vendor/bootstrap-5.0.2/css/bootstrap.min.css"/> -->
    <link rel="stylesheet" href="/assets/admin/vendor/fontawesome/css/all.min.css"/>

    <link rel="stylesheet" href="/assets/admin/css/style.css">
    <link rel="stylesheet" href="/assets/admin/css/components.css">
    <link rel="stylesheet" href="/assets/default/vendors/toast/jquery.toast.min.css">
</head>
<body>

<div id="app">
    @php
        $getPageBackgroundSettings = getPageBackgroundSettings();
    @endphp

    <section class="section">
        <div class="d-flex flex-wrap align-items-stretch">
            <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">

                @yield('content')

            </div>

            <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom" data-background="{{ $getPageBackgroundSettings['admin_login'] ?? '' }}">
            <div class="absolute-bottom-left index-2">
            <div class="text-light p-5 pb-2">
              <div class="mb-2 pb-3">
                <h1 class="mb-2 display-4 font-weight-bold">Rocket LMS</h1>
                <h5 class="font-weight-normal text-muted-transparent">fully-featured educational platform</h5>
              </div>
              All rights reserved for <a class="text-light bb" target="_blank" href="https://codecanyon.net/user/rocketsoft">Rocket Soft</a> on <a class="text-light bb" target="_blank" href="https://codecanyon.net/collections/10821267-rocket-lms-full-package">Codecanyon</a>
            </div>
          </div>
            </div>
          
        </div>
    </section>
</div>
<!-- General JS Scripts -->
<script src="/assets/admin/vendor/jquery/jquery-3.3.1.min.js"></script>
<script src="/assets/admin/vendor/poper/popper.min.js"></script>
<script src="/assets/admin/vendor/bootstrap/bootstrap.min.js"></script>
<script src="/assets/admin/vendor/nicescroll/jquery.nicescroll.min.js"></script>
<script src="/assets/admin/vendor/moment/moment.min.js"></script>
<script src="/assets/admin/js/stisla.js"></script>

<script src="/assets/default/vendors/toast/jquery.toast.min.js"></script>

<script>
    (function () {
        "use strict";

        @if(session()->has('toast'))
        $.toast({
            heading: '{{ session()->get('toast')['title'] ?? '' }}',
            text: '{{ session()->get('toast')['msg'] ?? '' }}',
            bgColor: '@if(session()->get('toast')['status'] == 'success') #43d477 @else #f63c3c @endif',
            textColor: 'white',
            hideAfter: 10000,
            position: 'bottom-right',
            icon: '{{ session()->get('toast')['status'] }}'
        });
        @endif
    })(jQuery);
</script>

<!-- Template JS File -->
<script src="/assets/admin/js/scripts.js"></script>
<script src="/assets/admin/js/custom.js"></script>

</body>
</html>
