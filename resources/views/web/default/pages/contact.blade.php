@extends(getTemplate().'.layouts.app')

@push('styles_top')
    <link rel="stylesheet" href="/assets/vendors/leaflet/leaflet.css">
@endpush

@php
    $rtlLanguages = !empty($generalSettings['rtl_languages']) ? $generalSettings['rtl_languages'] : [];

    $isRtl = ((in_array(mb_strtoupper(app()->getLocale()), $rtlLanguages)) or (!empty($generalSettings['rtl_layout']) and $generalSettings['rtl_layout'] == 1));
@endphp

@section('content')
<section class="contact-banner position-relative ">
  <div class="container h-100">
      <div class="row h-100 align-items-center">
          <div class="col-12 text-center">
              <h1 class="contact-heading text-white font-weight-normal">{{ trans('navbar.contact_us') }}</h1>
          </div>
      </div>
  </div>
</section>

    <!-- <div class="container"> -->
        <section class="contact-info-section">
            <div class="container">
                <div class="row d-none d-md-flex">
                    <div class="col-12 col-md-6 contact-item">
                        <div class="contact-info-box text-center">
                            <a href="https://api.whatsapp.com/send?phone=9720547734120" h >
                                <div class="contact-icon-title">
                                    <img src="/assets/default/img/phone.svg" class="@if($isRtl) ml-2 @else mr-2 @endif" />
                                    <span class="text-white">{{ trans('public.whatsapp') }}</span>
                                </div>
                                <p class="contact-subtitle font-14">+972-54-773-4120</p>
                            </a>
                            
                        </div>
                    </div>
                    <!-- <div class="col-12 col-md-4 contact-item">
                        <div class="contact-info-box text-center">
                            <div class="contact-icon-title">
                                <img src="/assets/default/img/address.svg" class="@if($isRtl) ml-2 @else mr-2 @endif"/>
                                <span>{{ trans('public.address') }}</span>
                            </div>
                            <p class="contact-subtitle font-14">Upper Macasandig, Cagayan de Oro City, Philippines</p>
                        </div>
                    </div> -->
                    <div class="col-12 col-md-6 contact-item">
                        <div class="contact-info-box text-center">
                            <div class="contact-icon-title">
                                <img src="/assets/default/img/email.svg" class="@if($isRtl) ml-2 @else mr-2 @endif"/>
                                <span class="text-white">{{ trans('public.email') }}</span>
                            </div>
                            <p class="contact-subtitle font-14">info@tulkka.com</p>
                        </div>
                    </div>
                </div>
                <div class="px-25 py-15 d-flex justify-content-between align-items-center d-md-none"> 
                    <a href="ss"><i class="fa-brands fa-instagram mr-2 social-icon text-white"></i></a> 
                    <a href="ss"><i class="fa-brands fa-facebook mr-2 social-icon text-white"></i></a> 
                    <a href="ss"><i class="fa-brands fa-whatsapp mr-2 social-icon text-white"></i></a> 
                    <a href="ss"><i class="fa-brands fa-tiktok mr-2 social-icon text-white"></i></a>
                    <a href="ss"><img src="/assets/default/img/email.svg" width="22" class=" mr-2 "></a> 
                    <a href="ss"><img src="/assets/default/img/address.svg" width="16" class=" mr-2 "></a> 
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-12 col-md-4">
                    <div class="contact-items mt-30 rounded-lg py-20 py-md-40 px-15 px-md-30 text-center">
                        <div class="contact-icon-box box-info p-20 d-flex align-items-center justify-content-center mx-auto">
                            <i data-feather="map-pin" width="50" height="50" class="text-white"></i>
                        </div>

                        <h3 class="mt-30 font-16 font-weight-bold text-dark-blue">{{ trans('site.our_address') }}</h3>
                        @if(!empty($contactSettings['address']))
                            <p class="font-weight-500 font-14 text-gray mt-10">{!! nl2br($contactSettings['address']) !!}</p>
                        @else
                            <p class="font-weight-500 text-gray font-14 mt-10">{{ trans('site.not_defined') }}</p>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="contact-items mt-30 rounded-lg py-20 py-md-40 px-15 px-md-30 text-center">
                        <div class="contact-icon-box box-green p-20 d-flex align-items-center justify-content-center mx-auto">
                            <i data-feather="phone" width="50" height="50" class="text-white"></i>
                        </div>

                        <h3 class="mt-30 font-16 font-weight-bold text-dark-blue">{{ trans('site.whatsapp') }}</h3>
                        @if(!empty($contactSettings['phones']))
                            <a href="https://api.whatsapp.com/send?phone={{$contactSettings['phones']}}" class="font-weight-500 text-gray font-14 mt-10">{!! nl2br(str_replace(',','<br/>',$contactSettings['phones'])) !!}</a>
                        @else
                            <p class="font-weight-500 text-gray font-14 mt-10">{{ trans('site.not_defined') }}</p>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="contact-items mt-30 rounded-lg py-20 py-md-40 px-15 px-md-30 text-center">
                        <div class="contact-icon-box box-red p-20 d-flex align-items-center justify-content-center mx-auto">
                            <i data-feather="mail" width="50" height="50" class="text-white"></i>
                        </div>

                        <h3 class="mt-30 font-16 font-weight-bold text-dark-blue">{{ trans('public.email') }}</h3>
                        @if(!empty($contactSettings['emails']))
                            <p class="font-weight-500 text-gray font-14 mt-10">{!! nl2br(str_replace(',','<br/>',$contactSettings['emails'])) !!}</p>
                        @else
                            <p class="font-weight-500 text-gray font-14 mt-10">{{ trans('site.not_defined') }}</p>
                        @endif
                    </div>
                </div>
            </div> -->
            
        </section>

        <div class="container">
            <section class="mt-30 mt-md-50 contact-form-section">
                <!-- <h2 class="font-16 font-weight-bold text-secondary">{{ trans('site.send_your_message_directly') }}</h2> -->

                @if(!empty(session()->has('msg')))
                    <div class="alert alert-success my-25 d-flex align-items-center">
                        <i data-feather="check-square" width="50" height="50" class="mr-2"></i>
                        {{ session()->get('msg') }}
                    </div>
                @endif

                <form action="/contact/store" method="post" class="mt-20">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-12">
                                    <div class="form-group">
                                        <label class="input-label font-weight-500">{{ trans('public.full_name') }}</label>
                                        <input type="text" placeholder="{{trans('public.name')}}" name="name" value="{{ old('name') }}" class="form-control br-5 @error('name')  is-invalid @enderror"/>
                                        @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-12">
                                    <div class="form-group">
                                        <label class="input-label font-weight-500">{{ trans('public.phone') }}</label>
                                        <input type="text" placeholder="{{trans('public.phone_number')}}"  name="phone" value="{{ old('phone') }}" class="form-control br-5 @error('phone')  is-invalid @enderror"/>
                                        @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-12">
                                    <div class="form-group">
                                        <label class="input-label font-weight-500">{{ trans('public.email') }}</label>
                                        <input type="text" placeholder="{{trans('public.email')}}" name="email" value="{{ old('email') }}" class="form-control br-5 @error('email')  is-invalid @enderror"/>
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-12">
                                    <div class="form-group">
                                        <label class="input-label font-weight-500">{{ trans('site.subject') }}</label>
                                        <input type="text" placeholder="{{trans('site.subject')}}" name="subject" value="{{ old('subject') }}" class="form-control br-5 @error('subject')  is-invalid @enderror"/>
                                        @error('subject')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                </div>                               
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label font-weight-500">{{ trans('site.message') }}</label>
                                <textarea name="message" id="" rows="7" class="form-control @error('message')  is-invalid @enderror">{{ old('message') }}</textarea>
                                @error('message')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary mt-20 px-md-80 w-md-100">{{ trans('public.send_message') }}</button>
                        </div>
                    </div>

                  
                    <!-- <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label font-weight-500">{{ trans('site.captcha') }}</label>
                                <div class="row align-items-center">
                                    <div class="col">
                                        <input type="text" name="captcha" class="form-control @error('captcha')  is-invalid @enderror">
                                        @error('captcha')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <div class="col d-flex align-items-center">
                                        <img id="captchaImageComment" class="captcha-image" src="">

                                        <button type="button" id="refreshCaptcha" class="btn-transparent ml-15">
                                            <i data-feather="refresh-ccw" width="24" height="24" class=""></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    
                </form>
            </section>
        </div>
        

    <!-- </div> -->
@endsection

@push('scripts_bottom')
    <script src="/assets/vendors/leaflet/leaflet.min.js"></script>

    <script src="/assets/default/js/parts/contact.min.js"></script>
@endpush
