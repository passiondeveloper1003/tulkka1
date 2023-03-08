@extends(getTemplate() . '.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section>
        <div class="d-flex flex-row justify-content-between">
            <h2 class="font-weight-normal font-18">{{ trans('panel.quiz_stats') }}</h2>
        </div>
        <div class="activities-container mt-20">
            <div class="row row-40">
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="d-flex justify-content-center stats-card">
                        <div class="d-flex flex-column align-items-center text-center mt-40">
                            <img src="/assets/default/img/homeworks.svg" width="36" height="36" alt="">
                            <strong class="font-36 font-weight-bold mt-20">{{ $quizes->total() ?? 0 }}</strong>
                            <span class="font-16 text-dark-blue text-gray mt-10">{{ trans('quiz.total_quizes') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-xl-3">
                    <div class=" d-flex justify-content-center stats-card">
                        <div class="d-flex flex-column align-items-center text-center mt-40">
                            <img src="/assets/default/img/homeworks2.svg" width="36" height="36" alt="">
                            <strong class="font-36 font-weight-bold mt-20">{{ $pending->count() ?? 0 }}</strong>
                            <span class="font-16 text-dark-blue text-gray mt-10">{{ trans('public.quizes_waiting') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="d-flex flex-column flex-md-row">
                

                
            </div> -->

        </div>
    </section>

    <section class="mt-25">
        <h2 class="font-18 font-weight-normal">{{ trans('quiz.filter_quizes') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/panel/homeworks" method="get" class="row">
                <div class="col-12 col-lg-5">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.from') }}</label>
                                <div class="input-group">
                                    <input type="text" name="from" autocomplete="off"
                                        class="form-control @if (!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                        aria-describedby="dateInputGroupPrepend" value="{{ request()->get('from', '') }}" />
                                    <span style="position:absolute; right:26px;top:12px;"
                                        class="fa-regular fa-calendar text-gray"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('public.to') }}</label>
                                <div class="input-group">
                                    <input type="text" name="to" autocomplete="off"
                                        class="form-control @if (!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                        aria-describedby="dateInputGroupPrepend" value="{{ request()->get('to', '') }}" />
                                    <span style="position:absolute; right:26px;top:12px;"
                                        class="fa-regular fa-calendar text-gray"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-5">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label class="input-label">{{ trans('quiz.teacher') }}</label>
                                <select name="people_id" class="form-control select2"
                                    data-placeholder="{{ trans('public.all') }}">
                                    <option value="all">{{ trans('public.all') }}</option>


                                    @if (!$authUser->isTeacher())
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}"
                                                @if (request()->get('people_id') == $teacher->id) selected @endif>
                                                {{ $teacher->full_name }}</option>
                                        @endforeach
                                    @else
                                        @foreach ($students as $student)
                                            <option value="{{ $student->id }}"
                                                @if (request()->get('people_id') == $student->id) selected @endif>
                                                {{ $student->full_name }}</option>
                                        @endforeach
                                    @endif

                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="row">

                                <div class="col-12 col-lg-12">
                                    <div class="form-group">
                                        <label class="input-label">{{ trans('public.status') }}</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="all">{{ trans('public.all') }}</option>
                                            <option value="active" @if (request()->get('status') == 'active') selected @endif>
                                                {{ trans('public.active') }}</option>
                                            <option value="pending" @if (request()->get('status') == 'pending') selected @endif>
                                                {{ trans('public.pending') }}</option>
                                            <option value="ended" @if (request()->get('status') == 'ended') selected @endif>
                                                {{ trans('public.ended') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-2 d-flex align-items-center justify-content-end">
                    <button type="submit"
                        class="btn btn-sm btn-primary w-100 mt-2 rounded">{{ trans('public.quiz_show_results') }}</button>
                </div>
            </form>
        </div>
    </section>

    <section class="mt-35">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="font-18 font-weight-normal">{{ trans('quiz.quiz_list') }}</h2>

            <form action="/panel/quizzes" method="get" class="">
                <div
                    class="d-flex align-items-center flex-row-reverse flex-md-row justify-content-start justify-content-md-center mt-20 mt-md-0">
                    <label class="mb-0 mr-10 cursor-pointer text-gray font-14 font-weight-500"
                        for="activeQuizzesSwitch">{{ trans('quiz.show_only_waiting_quizes') }}</label>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="active_quizzes" class="custom-control-input" id="activeQuizzesSwitch"
                            @if (request()->get('active_quizzes', null) == 'on') checked @endif>
                        <label class="custom-control-label" for="activeQuizzesSwitch"></label>
                    </div>
                </div>
            </form>
        </div>

        @if ($quizes->count() > 0)
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ trans('public.title') }}</th>
                                        @if (!$user->isTeacher())
                                            <th class="text-center">{{ trans('quiz.teachers') }}</th>
                                        @else
                                            <th class="text-center">{{ trans('quiz.students') }}</th>
                                        @endif
                                        <th class="text-center">{{ trans('public.quiz_result') }}</th>
                                        <th class="text-center">{{ trans('public.status') }}</th>
                                        <th class="text-center">{{ trans('public.created_at') }}</th>
                                        <th class="text-center">{{ trans('public.action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($quizes as $quiz)
                                        <tr>
                                            <td class="text-left">
                                                <span class="d-block">{{ $quiz->title }}</span>
                                            </td>
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ !$user->isTeacher() ? $quiz->teacher->full_name : $quiz->student->full_name }}
                                            </td>
                                            <td class="text-center align-middle">
                                                {{ $quiz->result ?? 'Not Evaluated' }}</td>
                                            <td class="text-center align-middle">
                                                @switch($quiz->status)
                                                    @case('pending')
                                                        <span
                                                            class="text-warning font-weight-500">{{ trans('public.pending') }}</span>
                                                    @break

                                                    @case('active')
                                                        <span
                                                            class="text-dark-blue font-weight-500">{{ trans('public.open') }}</span>
                                                    @break

                                                    @case('ended')
                                                        <span
                                                            class="text-primary font-weight-500">{{ trans('public.finished') }}</span>
                                                    @break

                                                    @case('canceled')
                                                        <span
                                                            class="text-danger font-weight-500">{{ trans('public.canceled') }}</span>
                                                    @break
                                                @endswitch
                                            </td>
                                            {{-- <td class="text-center align-middle">
                                                {{ $quiz->result ?? 'Not Evaluated' }}
                                            </td> --}}
                                            <td class="text-center align-middle">
                                                {{ \Carbon\Carbon::parse($quiz->created_at)->setTimezone($user->time_zone)->toDateTimeString() }}
                                            </td>
                                            <td class="text-center align-middle">
                                                <a href="/panel/quizes/{{ $quiz->id }}/details"
                                                    class="webinar-actions  mt-10 text-primary">{{ trans('public.open_detail') }}</a>
                                                <!-- <a href="/panel/quizes/{{ $quiz->id }}/delete"
                                                    class="webinar-actions mt-10 mx-2 ">
                                                    <img src="/assets/default/img/section-icons/evaluation.svg"
                                                        alt="">
                                                </a> -->
                                                <a href="/panel/quizes/{{ $quiz->id }}/delete"
                                                    class="webinar-actions mt-10 mx-2 ">
                                                    <img src="/assets/default/img/section-icons/close.svg"
                                                        alt="">
                                                </a>

                                                    @if ($authUser->isTeacher())
                                                    <a href="/panel/quizes/{{ $quiz->id }}/delete"
                                                        class="webinar-actions mt-10 mx-2 ">
                                                        <img src="/assets/default/img/section-icons/Vector-2.svg"
                                                            alt="">
                                                    </a>
                                                @endif

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
                'file_name' => 'quiz.png',
                'title' => trans('quiz.quiz_no_result'),
                'hint' => nl2br(trans('quiz.quiz_no_result_hint')),
            ])
        @endif
        @if ($authUser->isTeacher())
            <div class="w-100 d-flex justify-content-center mt-4"><a onclick='Livewire.emit("showQuizModal")'
                    class="btn btn-primary">Create new Quiz</a></div>
        @endif
    </section>

    <div class="my-30">
        {{ $quizes->links() }}
    </div>
    @livewire('quiz-modal')
@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>
    <script src="/assets/default/js/panel/quiz_list.min.js"></script>
@endpush
