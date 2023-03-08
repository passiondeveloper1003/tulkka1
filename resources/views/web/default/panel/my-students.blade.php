@extends(getTemplate() . '.panel.layouts.panel_layout')

@push('styles_top')
    <link rel="stylesheet" href="/assets/default/vendors/daterangepicker/daterangepicker.min.css">
    <link rel="stylesheet" href="/assets/default/vendors/select2/select2.min.css">
@endpush

@section('content')
    <section>
        <div class="d-flex flex-row justify-content-between">
            <h2 class="">{{ trans('panel.your_quizzes') }}</h2>
        </div>
        <div class="activities-container mt-25 p-20 p-lg-35">
            <div class="row">
                <div class="col-6 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/46.svg" width="64" height="64" alt="">
                        <strong
                            class="font-30 text-dark-blue font-weight-bold mt-5">{{ count($user->students()) ?? 0 }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('quiz.total_students') }}</span>
                    </div>
                </div>

                {{-- <div class="col-4 d-flex align-items-center justify-content-center">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="/assets/default/img/activity/48.svg" width="64" height="64" alt="">
                        <strong class="font-30 text-dark-blue font-weight-bold mt-5">{{ $userCount ?? 0 }}</strong>
                        <span class="font-16 text-gray font-weight-500">{{ trans('quiz.students') }}</span>
                    </div>
                </div> --}}

            </div>
        </div>
    </section>

    {{-- <section class="mt-25">
        <h2 class="section-title">{{ trans('quiz.filter_homeworks') }}</h2>

        <div class="panel-section-card py-20 px-25 mt-20">
            <form action="/panel/homeworks" method="get" class="row">
                <div class="col-12 col-lg-4">
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
                                        class="form-control @if (!empty(request()->get('from'))) datepicker @else datefilter @endif"
                                        aria-describedby="dateInputGroupPrepend" value="{{ request()->get('from', '') }}" />
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
                                        class="form-control @if (!empty(request()->get('to'))) datepicker @else datefilter @endif"
                                        aria-describedby="dateInputGroupPrepend" value="{{ request()->get('to', '') }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
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
                        class="btn btn-sm btn-primary w-100 mt-2">{{ trans('public.show_results') }}</button>
                </div>
            </form>
        </div>
    </section> --}}

    <section class="mt-35">
        <div class="d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row">
            <h2 class="section-title">{{ trans('quiz.students_list') }}</h2>

        </div>

        @if (count($user->students()))
            <div class="panel-section-card py-20 px-25 mt-20">
                <div class="row">
                    <div class="col-12 ">
                        <div class="table-responsive">
                            <table class="table text-center custom-table">
                                <thead>
                                    <tr>
                                        <th class="text-left">{{ trans('public.img') }}</th>
                                        <th class="text-left">{{ trans('public.student_goal') }}</th>
                                    </tr>

                                </thead>
                                <tbody>

                                    @foreach ($user->students() as $teacher)
                                        <tr>
                                            <td>
                                                <div class="user-inline-avatar d-flex align-items-center">
                                                    <div class="avatar bg-gray200">
                                                        <img src="{{ $teacher->getAvatar() }}" class="img-cover"
                                                            alt="">
                                                    </div>
                                                    <span class="d-block ml-2">{{ $teacher->full_name }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="d-flex">
                                                    @if (isset($teacher->lessonsForStudent()->first()->student_goal))
                                                        @php
                                                            $goals = explode(',', $teacher->lessonsForStudent()->first()->student_goal);
                                                        @endphp
                                                        @foreach ($goals as $goal)
                                                            {{ trans('update.' . $goal) }}
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </span>
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
                'title' => trans('quiz.no_student_result'),
                'hint' => nl2br(trans('quiz.no_student_result')),
            ])
        @endif

    </section>


@endsection

@push('scripts_bottom')
    <script src="/assets/default/vendors/daterangepicker/daterangepicker.min.js"></script>
    <script src="/assets/default/vendors/select2/select2.min.js"></script>

    <script src="/assets/default/js/panel/quiz_list.min.js"></script>
@endpush
