@extends(getTemplate() . '.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
@endpush

@section('content')

    <section>
        <h2 class="section-title">{{ trans('panel.filter_feedbacks') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="" method="get" class="row">
                <div class="col-12 col-lg-5">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.from') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="from" autocomplete="off"
                                        value="{{ request()->get('from') }}"
                                        class="form-control {{ !empty(request()->get('from')) ? 'datepicker' : 'datefilter' }}"
                                        aria-describedby="dateInputGroupPrepend" />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.to') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="dateInputGroupPrepend">
                                            <i data-feather="calendar" width="18" height="18" class="text-white"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="to" autocomplete="off"
                                        value="{{ request()->get('to') }}"
                                        class="form-control {{ !empty(request()->get('to')) ? 'datepicker' : 'datefilter' }}"
                                        aria-describedby="dateInputGroupPrepend" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="form-group">
                        <label class="input-label">{{ trans('panel.webinar') }}</label>
                        <input type="text" name="webinar" value="{{ request()->get('webinar') }}" class="form-control" />
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit"
                        class="btn btn-sm btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mt-35">
        <h2 class="section-title">{{ trans('panel.my_feedbacks') }}</h2>

        @if (isset($feedbacks) and $feedbacks->count() > 0)
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table custom-table text-center ">
                                <thead>
                                    <tr>
                                        <th class="text-gray text-center">{{ trans('panel.grammar_rate') }}</th>
                                        <th class="text-gray text-center">{{ trans('panel.pron_rate') }}</th>
                                        <th class="text-gray text-center">{{ trans('panel.speaking_rate') }}</th>
                                        <th class="text-gray text-center">{{ trans('panel.grammar') }}</th>
                                        <th class="text-gray text-center">{{ trans('panel.pron') }}</th>
                                        <th class="text-gray text-center">{{ trans('panel.comment') }}</th>
                                        <th class="text-gray text-center">{{ trans('panel.speaking') }}</th>
                                        <th class="text-gray text-center">{{ trans('panel.details') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($feedbacks as $feedback)
                                        <tr>
                                            <td class="align-middle">
                                                <div class="d-flex flex-row">
                                                    @foreach (range(1, $feedback->grammar_rate) as $index => $grammar_rate)
                                                        <svg style="width: 32px; height: 32px;"
                                                            class="cursor-pointer block @if ($feedback->grammar_rate > $index) text-warning @endif"
                                                            fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                        </svg>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-row">
                                                    @foreach (range(1, $feedback->pronunciation_rate) as $index => $pronunciation_rate)
                                                        <svg style="width: 32px; height: 32px;"
                                                            class="cursor-pointer block @if ($feedback->pronunciation_rate > $index) text-warning @endif"
                                                            fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                        </svg>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex flex-row">
                                                    @foreach (range(1, $feedback->speaking_rate) as $index => $speaking_rate)
                                                        <svg style="width: 32px; height: 32px;"
                                                            class="cursor-pointer block @if ($feedback->speaking_rate > $index) text-warning @endif"
                                                            fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                                        </svg>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <p class="">{{ Str::limit($feedback->grammar, 30) }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <p class="">{{ Str::limit($feedback->pronunciation, 30) }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <p class="">{{ Str::limit($feedback->comment, 30) }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <p class="">{{ Str::limit($feedback->speaking, 30) }}</p>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ url('/panel/feedbacks/' . $feedback->id) }}"
                                                    class="btn btn-primary btn-sm">Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @include(getTemplate() . '.includes.no-result', [
                'file_name' => 'comment.png',
                'title' => trans('panel.my_comments_no_result'),
                'hint' => nl2br(trans('panel.my_comments_no_result_hint')),
            ])
        @endif
    </section>

    <div class="my-30">
        {{ $feedbacks->links() }}
    </div>
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/moment.min.js"></script>
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script>
        var commentLang = '{{ trans('panel.comment') }}';
        var replyToCommentLang = '{{ trans('panel.reply_to_the_comment') }}';
        var editCommentLang = '{{ trans('panel.edit_comment') }}';
        var saveLang = '{{ trans('public.save') }}';
        var closeLang = '{{ trans('public.close') }}';
        var failedLang = '{{ trans('quiz.failed') }}';
    </script>
    <script src="/assets/default/js/panel/comments.min.js"></script>
@endpush
