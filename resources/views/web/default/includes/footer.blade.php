@php
    $socials = getSocials();
    if (!empty($socials) and count($socials)) {
        $socials = collect($socials)
            ->sortBy('order')
            ->toArray();
    }

    $footerColumns = getFooterColumns();
@endphp

<footer class="footer bg-white position-relative user-select-none">
    <div class="bg-panel-bg py-30 py-md-70 d-flex align-items-center">
        <div class="container ">
            <div class="row">
                <div class="col-12">
                    <div class="position-absolute custom-gift d-none d-md-block">
                        <img src="/assets/default/img/gift.png" alt="" />
                    </div>
                    <div class="position-absolute custom-gift-2 d-none d-md-block">
                        <img src="/assets/default/img/gift-r.png" alt="" />
                    </div>
                    <div
                        class="footer-subscribe d-flex align-items-center justify-content-between flex-column newsletter-container text-center">
                        <div class="newsletter-title text-white text-wrap">{{ trans('footer.join_us_today') }}</div>
                        <div class="rounded w-md-100">
                            <form action="/newsletters" method="post">
                                {{ csrf_field() }}
                                <input type="text" name="newsletter_email"
                                    class="newsletter-form w-md-100 newsletter-email form-control rounded border-0 @error('newsletter_email') is-invalid @enderror"
                                    placeholder="{{ trans('footer.enter_email_here') }}" />
                            </form>
                        </div>
                        <button type="submit"
                            class="btn bg-white text-primary rounded px-50 py-20 mt-4 text-uppercase newsletter-btn">{{ trans('footer.join') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $columns = ['first_column', 'second_column', 'third_column', 'forth_column'];
    @endphp

    <div class="container-fluid py-100 px-md-50 px-lg-100" dir="@if($isRtl) rtl @else ltr @endif">
        <div class="row">
            @foreach ($columns as $index => $column)
                @if ($loop->first)
                    <div class="col-12 col-xl-4 text-center text-xl-left mb-20 mb-xl-0">
                        @if (!empty($footerColumns[$column]))
                            @if (!empty($footerColumns[$column]['title']) && $footerColumns[$column]['title'] != 'main')
                                <span
                                    class="header d-block  font-weight-bold">{{ $footerColumns[$column]['title'] }}</span>
                            @endif

                            @if (!empty($footerColumns[$column]['value']))
                                <div class="@if ($footerColumns[$column]['title'] != 'main') mt-20 @endif text-dark footer-branding-title">
                                    {!! $footerColumns[$column]['value'] !!}
                                </div>
                            @endif
                        @endif
                    </div>
                @elseif($index < count($columns) - 1)
                    <div class="col-6 col-lg-4 col-xl-2 mt-30 mt-xl-0">
                        @if (!empty($footerColumns[$column]))
                            @if (!empty($footerColumns[$column]['title']) && $footerColumns[$column]['title'] != 'main')
                                <span
                                    class="header d-block  font-weight-bold footer-title">{{ $footerColumns[$column]['title'] }}</span>
                            @endif

                            @if (!empty($footerColumns[$column]['value']))
                                <div class="@if ($footerColumns[$column]['title'] != 'main') mt-20 @endif text-dark ">
                                    {!! $footerColumns[$column]['value'] !!}
                                </div>
                            @endif
                        @endif
                    </div>
                @else
                    <div class="col-6 col-lg-4 col-xl-4 mt-30 mt-xl-0 d-none d-lg-block">
                        @if (!empty($footerColumns[$column]))
                            @if (!empty($footerColumns[$column]['title']) && $footerColumns[$column]['title'] != 'main')
                                <span
                                    class="header d-block  font-weight-bold footer-title">{{ $footerColumns[$column]['title'] }}</span>
                            @endif

                            @if (!empty($footerColumns[$column]['value']))
                                <div class="@if ($footerColumns[$column]['title'] != 'main') mt-20 @endif text-dark ">
                                    {!! $footerColumns[$column]['value'] !!}
                                </div>
                            @endif
                        @endif
                    </div>
                @endif

            @endforeach

        </div>
    </div>
    <div class="bg-primary" dir="ltr">
        <div class="container">
            <div class="row">
                <div class="text-white py-10 d-flex align-items-center justify-content-center justify-content-md-between col-12 ">
                    <span class="footer-bottom-left">© Copyright 2023 by Tulkka</span>
                    <div class="payments d-none d-md-flex align-items-center">
                        {{-- <img class="mr-2" src="/assets/default/img/stripe.png" alt="" />
                        <img src="/assets/default/img/paypal.png" alt="" /> --}}
                    </div>
                </div>
            </div>

        </div>
    </div>
</footer>
