@extends(getTemplate() . '.layouts.app')

@section('content')
    <div class="container">
        @if (!empty(session()->has('msg')))
            <div class="alert alert-info alert-dismissible fade show mt-30" role="alert">
                {{ session()->get('msg') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row login-container">

            <div class="col-12 col-md-6 pl-0">
                <img src="{{ getPageBackgroundSettings('login') }}" class="img-cover" alt="Login">
            </div>
            <div class="col-12 col-md-6">
                <div class="login-card">
                    <h1 class="font-20 font-weight-bold">{{ trans('auth.login_h1') }}</h1>

                    <div class="mt-35">
                        @livewire('login-otp')
                    </div>

                    <div class="text-center mt-20">
                        <span
                            class="badge badge-circle-gray300 text-secondary d-inline-flex align-items-center justify-content-center">{{ trans('auth.or') }}</span>
                    </div>

                    <a href="/google" target="_blank"
                        class="social-login mt-20 p-10 text-center d-flex align-items-center justify-content-center">
                        <img src="/assets/default/img/auth/google.svg" class="mr-auto" alt=" google svg" />
                        <span class="flex-grow-1">{{ trans('auth.google_login') }}</span>
                    </a>

                    <a href="{{ url('/facebook/redirect') }}" target="_blank"
                        class="social-login mt-20 p-10 text-center d-flex align-items-center justify-content-center ">
                        <img src="/assets/default/img/auth/facebook.svg" class="mr-auto" alt="facebook svg" />
                        <span class="flex-grow-1">{{ trans('auth.facebook_login') }}</span>
                    </a>

                    <div class="mt-30 text-center">
                        <a href="/forget-password" target="_blank">{{ trans('auth.forget_your_password') }}</a>
                    </div>

                    <div class="mt-20 text-center">
                        <span>{{ trans('auth.dont_have_account') }}</span>
                        <a href="/register" class="text-secondary font-weight-bold">{{ trans('auth.signup') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
