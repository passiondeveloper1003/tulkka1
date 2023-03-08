@extends(getTemplate() . '.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section class="container-fluid">
        <div class="row default-row">
            <div class="col-12 col-lg-4 d-flex">
                <div class="col-12 col-md-12 d-flex flex-column align-items-center panel-section-card">
                    <div class="form-group mt-4 ml-2 w-100 text-center">
                        <label class="font-weight-normal font-20" for="comment">
                            Grammar</label>
                        <div class="mt-4">{!! $feedback->grammar !!}</div>
                        <div class="position-absolute">
                            <div class="mt-4">Your Rate</div>
                            <div class="d-flex flex-row align-items-center justify-content-center">
                                @foreach (range(1, $feedback->grammar_rate) as $index => $grammar_rate)
                                    <svg style="width: 32px; height: 32px;"
                                        class="cursor-pointer block text-primary  @if ($feedback->grammar_rate > $index) text-warning @endif"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                @endforeach
                            </div>
                        </div>
                       
                        <div class="invalid-feedback">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
            @php

                preg_match_all('/^-.*/m', $feedback->pronunciation, $words_array);
                $list = explode(PHP_EOL, $feedback->pronunciation);

            @endphp
            <div class="col-12 col-lg-4 d-flex">
                <div class="col-12 col-md-12 d-flex flex-column align-items-center panel-section-card">
                    <div class="form-group mt-4 ml-2 w-100 text-center">
                        <label class="font-weight-normal font-20" for="comment">
                            Pronunciation</label>

                        <div class="mt-4 talk-container">
                            @forelse($list as $word)
                                @if (in_array($word, $words_array[0]))
                                    <div class="talk">
                                        <span class="word">{{ $word }}</span>
                                        <i class="fa-solid fa-volume-high text-primary"></i>
                                    </div>
                                @else
                                    <span>
                                        {{ $word }}
                                    </span>
                                @endif

                            @empty
                                <span></span>
                            @endforelse
                            <span>

                            </span>
                        </div>
                        <div class="invalid-feedback">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="mt-4">Your Rate</div>
                        <div class="d-flex flex-row align-items-center justify-content-center">
                            @foreach (range(1, $feedback->pronunciation_rate) as $index => $pronunciation_rate)
                                <svg style="width: 32px; height: 32px;"
                                    class="cursor-pointer block text-primary  @if ($feedback->pronunciation_rate > $index) text-warning @endif"
                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endforeach


                        </div>
                    </div>
                </div>

            </div>

            <div class="col-12 col-lg-4 d-flex">
                <div class="col-12 d-flex flex-column align-items-center panel-section-card">
                    <div class="form-group mt-4 ml-2 w-100 text-center">
                        <label class="font-weight-normal font-20" for="comment">
                            Speaking</label>
                        <div class="mt-4">{!! $feedback->speaking !!}</div>
                        <div class="invalid-feedback">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                        <div class="mt-4">Your Rate</div>
                        <div class="d-flex flex-row align-items-center justify-content-center">
                            @foreach (range(1, $feedback->speaking_rate) as $index => $speaking_rate)
                                <svg style="width: 32px; height: 32px;"
                                    class="cursor-pointer block text-primary  @if ($feedback->speaking_rate > $index) text-warning @endif"
                                    fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path
                                        d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                </svg>
                            @endforeach

                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="d-flex p-4 mt-30 col-12">
                <div class="col-12 d-flex flex-column align-items-center panel-section-card">
                    <div class="form-group mt-4 ml-2 w-100">
                        <label class="font-weight-normal font-20 text-center w-100" for="comment">
                            Teacher Comment</label>
                        <div class="d-flex flex-row align-items-center mt-4">

                            <div class="ml-4">{!! $feedback->comment !!}</div>
                        </div>

                        <div class="invalid-feedback">
                            @error('title')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <a class="btn btn-primary btn-sm rounded mt-20" href="{{ url('/panel') }}">Back To Dashboard</a>
            </div>
        </div>

    </section>
@endsection
@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
@endpush
